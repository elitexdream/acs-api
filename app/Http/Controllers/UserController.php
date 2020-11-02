<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use Validator;
use Auth;
// use Laravel\Passport\Client as OClient;

class UserController extends Controller
{
	public function login(Request $request)
	{
	    $validator = Validator::make($request->all(), [ 
	        'username' => 'required|email', 
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
    			'localhost/oauth/token',
    			[
    				'form_params' => [
	    				'grant_type' => 'password',
	    				'client_id' => 4,
	    				'client_secret' => 'swYKxHKa1RjKoQprk1MrkVUoOmJx1YhTKtvEYWPY',
	    				'username' => $request->username,
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

    // public function register(Request $request) { 
    //     $validator = Validator::make($request->all(), [ 
    //         'name' => 'required', 
    //         'email' => 'required|email|unique:users', 
    //         'password' => 'required', 
    //         'c_password' => 'required|same:password', 
    //     ]);

    //     if ($validator->fails()) { 
    //         return response()->json(['error'=>$validator->errors()], 401);            
    //     }

    //     $password = $request->password;
    //     $input = $request->all(); 
    //     $input['password'] = bcrypt($input['password']); 
    //     $user = User::create($input); 
    //     $oClient = OClient::where('password_client', 1)->first();
    //     return $this->getTokenAndRefreshToken($oClient, $user->email, $password);
    // }

    // public function getTokenAndRefreshToken(OClient $oClient, $email, $password) { 
    //     $oClient = OClient::where('password_client', 1)->first();
    //     $http = new OClient;
    //     $response = $http->request('POST', 'http://localhost:8000/oauth/token', [
    //         'form_params' => [
    //             'grant_type' => 'password',
    //             'client_id' => $oClient->id,
    //             'client_secret' => $oClient->secret,
    //             'username' => $email,
    //             'password' => $password,
    //             'scope' => '*',
    //         ],
    //     ]);

    //     $result = json_decode((string) $response->getBody(), true);
    //     return response()->json($result, 200);
    // }

    public function check()
    {
    	if (Auth::guard('api')->check())
    	{
	        return response()->json(true);
    	}
    	else
    	{
    		return response()->json(false);
    	}
    }
}
