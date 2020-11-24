<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use GuzzleHttp\Client;
use Hash;
use Carbon\Carbon;
use Mail;

use App\Mail\PasswordReset;
use App\Mail\CustomerInvitation;

use App\User;
use App\Company;
use App\Role;
use App\Location;
use App\Zone;
use App\UserRole;

class UserController extends Controller
{
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

        return response()->json('Email sent successfully.', 201);
    }

    public function check(Request $request) {
    	if (Auth::guard('api')->check()) {
            $user = $request->user('api');
            $user->role = $user->roles->first()->key;
            
            return response()->json($user);
    	} else {
    		return response()->json(false);
    	}
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


    public function initCreateAccount() {
        $roles = Role::whereIn('key', ['customer_manager', 'customer_operator'])->get();
        $locations = Location::get();
        $zones = Zone::get();

        return response()->json(compact('roles', 'locations', 'zones'));
    }

    public function initEditAccount($id) {
        $user = User::findOrFail($id);
        $roles = Role::whereIn('key', ['customer_manager', 'customer_operator'])->get();
        $locations = Location::get();
        $zones = Zone::get();
        $user->role = $user->roles->first()->id;
        $user->selected_locations = $user->locations->pluck('id');
        $user->selected_zones = $user->zones->pluck('id');

        return response()->json(compact('roles', 'locations', 'zones', 'user'));
    }

    public function getCompanyUsers(Request $request) {
        $company = Company::where('user_id', $request->user('api')->id)->first();

        $users = $company->users;
        foreach ($users as $key => $user) {
            $user->role = $user->roles->first();
        }

        return response()->json(compact('users'));
    }

    public function addCompanyUser(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }


        $password_string = md5(uniqid($request->email, true));
        
        // need some changes on user_id
        $company = Company::where('user_id', $request->user('api')->id)->first();

        $password_string = md5(uniqid($request->email, true));
        
        $user = $company->users()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password_string),
        ]);

        $user->roles()->attach($request->role);

        $user->locations()->attach($request->locations);

        //need updates
        $user->zones()->attach($request->zones);

        Mail::to($user->email)->send(new CustomerInvitation($password_string));

        return response()->json('Created successfully.', 201);
    }

    public function updateCompanyUserAccount(Request $request, $id) {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        $user->save();

        $user->roles()->sync([$request->role]);
        $user->locations()->sync($request->locations);

        //need updates
        $user->zones()->sync($request->zones);

        return response()->json('Updated successfully.');
    }
}
