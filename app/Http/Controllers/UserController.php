<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use Validator;
use Auth;
use GuzzleHttp\Client;
use Hash;
use Carbon\Carbon;

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
}
