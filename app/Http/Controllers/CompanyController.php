<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomerInvitation;
use Mail;
use Validator;
use App\User;
use App\Company;
use App\Profile;
use App\City;

class CompanyController extends Controller
{
	public function index()
	{
		$companies = Company::select('id', 'name', 'user_id', 'created_at')->get();

		foreach ($companies as $company) {
			$company->administratorName = $company->customer->name;
		}

		return response()->json(compact('companies'), 200);
	}

    public function addCustomer(Request $request)
	{
	    $validator = Validator::make($request->all(), [ 
	        'company_name' => 'required',
	        'administrator_name' => 'required',
	        'administrator_email' => 'required|email|max:255|unique:users,email',
	        'address_1' => 'required',
	        'address_2' => 'required',
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
		// $password_string = 'password';
		
        $user = User::create([
            'name' => $request->administrator_name,
            'email' => $request->administrator_email,
            'password' => bcrypt($password_string),
        ]);

		$user->profile->update([
			'address_1' => $request->address_1,
	        'address_2' => $request->address_2,
	        'zip' => $request->zip,
	        'state' => $request->state,
	        'city' => $request->city,
	        'country' => $request->country,
	        'phone' => $request->phone
		]);
		$user->roles()->attach(ROLE_CUSTOMER_ADMIN);

		$company = Company::create([
            'user_id' => $user->id,
            'name' => $request->company_name
        ]);

        Mail::to($user->email)->send(new CustomerInvitation($password_string));

        return response()->json('Created successfully.', 201);
    }

    public function getCustomer(Request $request, $id)
	{
		$company = Company::findOrFail($id);
		$company->administratorName = $company->customer->name;
		$company->administratorEmail = $company->customer->email;
		$profile = $company->customer->profile;
		$profile->id = $id;
		$cities = City::where('state', $profile->state);
		
		return response()->json(compact('company', 'profile', 'cities'));
	}

	public function updateCustomerAccount(Request $request, $id)
	{
		$company = Company::findOrFail($id);
        $customer = $company->customer;

		$validator = Validator::make($request->all(), [ 
	        'name' => 'required',
	        'administrator_name' => 'required',
	        'administrator_email' => 'required|email|max:255|unique:users,email,' . $customer->id
	    ]);

	    if ($validator->fails())
	    {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $company->name = $request->name;
        $customer->name = $request->administrator_name;
        $customer->email = $request->administrator_email;

        $company->save();
        $customer->save();

        return response()->json('Updated Successfully.', 200);
	}

	public function updateCustomerProfile(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'address_1' => 'required',
	        'address_2' => 'required',
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

        $company = Company::findOrFail($id);
        $profile = $company->customer->profile;

        $profile->address_1 = $request->address_1;
		$profile->address_2 = $request->address_2;
		$profile->zip = $request->zip;
		$profile->state = $request->state;
		$profile->city = $request->city;
		$profile->country = $request->country;
		$profile->phone = $request->phone;

        $profile->save();

        return response()->json('Updated Successfully.', 200);
	}

	public function testMail(Request $request)
	{

		Mail::to($request->to)->send(new CustomerInvitation);
	}

	public function testSMS(Request $request)
	{
		$sid = env('TWILIO_ACCOUNT_SID');
		$token = env('TWILIO_AUTH_TOKEN');

		$twilio_number = "18622256236";
		$client = new \Twilio\Rest\Client($sid, $token);

		$client->messages->create(
    		$request->to,
		    array(
		        'from' => $twilio_number,
		        'body' => 'I sent this message in under 10 minutes!'
		    )
		);
	}
}
