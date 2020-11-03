<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use Validator;
use Auth;
use GuzzleHttp\Client;
use Hash;

class UserController extends Controller
{
	public function login(Request $request)
	{
	    $validator = Validator::make($request->all(), [ 
	        'email' => 'required|email', 
	        'password' => 'required', 
	    ]);

	    if ($validator->fails())
	    {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

    	$http = new \GuzzleHttp\Client();

    	try
    	{
    		$response = $http->post(
    			'localhost/acs-api/public/oauth/token',
    			[
    				'form_params' => [
	    				'grant_type' => env('PASSPORT_GRANT_TYPE'),
	    				'client_id' => env('PASSPORT_CLIENT_ID'),
	    				'client_secret' => env('PASSPORT_CLIENT_SECRET'),
	    				'username' => $request->email,
	    				'password' => $request->password
                    ]
    		]);
    		return $response->getBody();
    	}
    	catch(\GuzzleHttp\Exception\BadResponseException $e)
    	{
    		if($e->getCode() === 400)
    		{
    			return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
    		}
    		return response()->json('Something went wrong on the server.', $e->getCode());
    	}
    }

    // function register(Request $request)
    // {
    //     /**
    //      * Get a validator for an incoming registration request.
    //      *
    //      * @param  array  $request
    //      * @return \Illuminate\Contracts\Validation\Validator
    //      */
    //     $valid = validator($request->only('email', 'name', 'password'), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     if ($valid->fails()) {
    //         $jsonError=response()->json($valid->errors()->all(), 400);
    //         return \Response::json($jsonError);
    //     }

    //     $data = request()->only('email','name','password');

    //     $user = User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => bcrypt($data['password']),
    //     ]);

    //     // // And created user until here.

    //     // $client = Client::where('password_client', 1)->first();

    //     // // Is this $request the same request? I mean Request $request? Then wouldn't it mess the other $request stuff? Also how did you pass it on the $request in $proxy? Wouldn't Request::create() just create a new thing?

    //     // $request->request->add([
    //     //     'grant_type'    => env('PASSPORT_GRANT_TYPE'),
    //     //     'client_id'     => $client->id,
    //     //     'client_secret' => $client->secret,
    //     //     'username'      => $data['email'],
    //     //     'password'      => $data['password'],
    //     //     'scope'         => null,
    //     // ]);

    //     // // Fire off the internal request. 
    //     // $token = Request::create(
    //     //     'oauth/token',
    //     //     'POST'
    //     // );

    //     $http = new \GuzzleHttp\Client();

    //     try
    //     {
    //         $response = $http->post(
    //             'localhost/acs-api/public/oauth/token',
    //             [
    //                 'form_params' => [
    //                     'grant_type' => env('PASSPORT_GRANT_TYPE'),
    //                     'client_id' => env('PASSPORT_CLIENT_ID'),
    //                     'client_secret' => env('PASSPORT_CLIENT_SECRET'),
    //                     'username' => $request->email,
    //                     'password' => $request->password
    //                 ]
    //         ]);
    //         return $response->getBody();
    //     }
    //     catch(\GuzzleHttp\Exception\BadResponseException $e)
    //     {
    //         if($e->getCode() === 400)
    //         {
    //             return response()->json('Your credentials are incorrect. Please try again.', $e->getCode());
    //         }
    //         return response()->json('Something went wrong on the server.', $e->getCode());
    //     }
    // }

    public function logout(Request $request)
    {
        $request->user('api')->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function check(Request $request)
    {
    	if (Auth::guard('api')->check())
    	{
            return response()->json($request->user('api'));
    	}
    	else
    	{
    		return response()->json(false);
    	}
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'current_password' => 'required|min:6|max:200',
            'new_password' => 'required|min:6|max:200',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $user = $request->user('api');

        if(!Hash::check($request->current_password, $user->password))
        {
            return response()->json(['error' => 'Current password incorrect.'], 400);
        }
        else
        {
            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json(['message' => 'Successfully updated.'], 200);
        }
    }
}
