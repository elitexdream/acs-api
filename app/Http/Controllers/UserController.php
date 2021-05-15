<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use GuzzleHttp\Client;
use Hash;
use Carbon\Carbon;
use Mail;
use DB;

use App\Traits\MailTrait;

use App\Mail\PasswordReset;
use App\Mail\CustomerInvitation;

use App\User;
use App\Company;
use App\Role;
use App\Location;
use App\Zone;
use App\UserRole;
use App\City;

class UserController extends Controller
{
    use MailTrait;

	public function login(Request $request) {
	    $validator = Validator::make($request->all(), [ 
	        'email' => 'required|email', 
	        'password' => 'required', 
	    ]);

	    if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Email and password incorrect.'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('acs');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function logout(Request $request) {
        $request->user('api')->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function passwordReset(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json('Email not found', 404);
        }

        $password_string = md5(uniqid($request->email, true));
        $user->password = bcrypt($password_string);
        $user->save();

        Mail::to($request->email)->send(new PasswordReset($password_string));

        return response()->json('Email sent successfully.');
    }

    public function check(Request $request) {
    	if (Auth::guard('api')->check()) {
            $user = $request->user('api');
            if(!empty($request->role)) {
                if(!$user->hasRole($request->role))
                    return response()->json(false);
            }
            $user->role = $user->roles->first()->key;
            
            return response()->json($user);
    	} else {
    		return response()->json(false);
    	}
    }

    public function getUser(Request $request) {
        $user = $request->user('api');
        $user->profile = $user->profile;

        return response()->json(compact('user'));
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'current_password' => 'required',
            'new_password' => 'required|min:6|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $user = $request->user('api');

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password incorrect.'], 400);
        } else {
            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json(['message' => 'Successfully updated.'], 200);
        }
    }

    public function getTimezones() {
        $timezones = DB::Table('timezones')->get();

        return response()->json(compact('timezones'));
    }

    public function updateTimezone(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'timezone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $profile = $request->user('api')->profile;

        $timezone = DB::Table('timezones')->where('id', $request->timezone)->first();

        if($timezone) {
            $profile->timezone = $request->timezone;
            $profile->save();

            return response()->json(['message' => 'Successfully updated.'], 200);
        } else {
            return response()->json(['message' => 'Can\'t find timezone'], 404);
        }
    }

    public function edit(User $user) {
        $user->role = $user->roles->first()->id;
        $user->selected_locations = $user->locations->pluck('id');
        $user->selected_zones = $user->zones->pluck('id');

        $profile = $user->profile;

        $user->address_1 = $profile->address_1;
        $user->zip = $profile->zip;
        $user->state = $profile->state;
        $user->city = $profile->city;
        $user->country = $profile->country;
        $user->phone = $profile->phone;

        $cities = City::where('state', $profile->state)->orderBy('city')->get();

        return response()->json(compact('user', 'cities'));
    }

    public function index(Request $request) {
        $user = $request->user('api');
        if($user->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
            $ids = UserRole::whereNotIn('role_id', [ROLE_SUPER_ADMIN])->pluck('user_id');
            $users = User::whereIn('id', $ids)->get();
        } else {
            $users = $user->company->users;
        }

        foreach ($users as $key => $user) {
            $user->role = $user->roles->first();

            // add company name of the user
            $user->company_name = Company::where('id', $user->company_id)->first()->name;
        }

        return response()->json(compact('users'));
    }

    public function store(Request $request) {
        // this can be go in to authorization hook
        if(!$request->user('api')->hasRole(['acs_admin', 'customer_admin'])) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|exists:roles,id',
            'address_1' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $password_string = md5(uniqid($request->email, true));
        
        if(in_array($request->role, [ROLE_ACS_ADMIN, ROLE_ACS_MANAGER, ROLE_ACS_VIEWER])) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password_string),
                'company_id' => $request->user('api')->company_id,
            ]);

            $user->roles()->attach($request->role);

            $user->profile->update([
                'address_1' => $request->address_1,
                'zip' => $request->zip,
                'state' => $request->state,
                'city' => $request->city,
                'country' => $request->country,
                'phone' => $request->phone
            ]);

            $this->sendRegistrationMail($user, $password_string);
            // Mail::to($user->email)->send(new CustomerInvitation($password_string));
        } else {
            $company = $request->user('api')->company;

            $user = $company->users()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($password_string),
            ]);

            $user->roles()->attach($request->role);

            if($request->role != ROLE_CUSTOMER_ADMIN) {
                // add locations and zones only to customer operator and customer manager, not customer admin
                $user->locations()->attach($request->locations);
                $user->zones()->attach($request->zones);
            }

            $user->profile->update([
                'address_1' => $request->address_1,
                'zip' => $request->zip,
                'state' => $request->state,
                'city' => $request->city,
                'country' => $request->country,
                'phone' => $request->phone
            ]);

            Mail::to($user->email)->send(new CustomerInvitation($password_string));
        }
        
        return response()->json('Created successfully.', 201);
    }

    public function update(User $user, Request $request) {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'address_1' => 'required',
            'zip' => 'required',
            'state' => 'required',
            'city' => 'required',
            'country' => 'required',
            'phone' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        $profile = $user->profile;

        $profile->address_1 = $request->address_1;
        $profile->zip = $request->zip;
        $profile->state = $request->state;
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->phone = $request->phone;

        $user->update();
        $profile->update();

        $user->roles()->sync([$request->role]);

        if($request->role == ROLE_CUSTOMER_ADMIN || $request->role == ROLE_ACS_ADMIN || $request->role == ROLE_ACS_MANAGER || $request->role == ROLE_ACS_VIEWER) {
            $user->locations()->sync([]);
            $user->zones()->sync([]);
        } else {
            $user->locations()->sync($request->locations);
            $user->zones()->sync($request->zones);
        }

        return response()->json('Updated successfully.');
    }

    public function deleteUser(Request $request) {
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json('Email not found', 404);
        }

        $user->role()->delete();
        $user->profile()->delete();

        $user->delete();

        return response()->json('Deleted successfully.');
    }
}
