<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomerInvitation;
use App\traits\MailTrait;
// use Mail;
use Validator;
use App\User;
use App\Company;
use App\Profile;
use App\City;
use App\Role;
use App\UserRole;

class CompanyController extends Controller
{
	use MailTrait;

	public function index()
	{
		$customer_admin_role = Role::findOrFail(ROLE_CUSTOMER_ADMIN);
		$customer_admins = $customer_admin_role->users;

		$companies = Company::select('id', 'name', 'created_at')->get();

		foreach ($customer_admins as $customer_admin) {
			$customer_admin->companyName = $customer_admin->company->name;
			$customer_admin->administratorName = $customer_admin->name;
		}

		return response()->json(compact('customer_admins'));
	}

	/*
		Get all companies
	*/
	public function getCompanies() {
		$companies = Company::orderBy('name')->get();

		return response()->json(compact('companies'));
	}

    public function addCustomer(Request $request)
	{
	    $validator = Validator::make($request->all(), [ 
	        'company_name' => 'required',
	        'administrator_name' => 'required',
	        'administrator_email' => 'required|email|max:255|unique:users,email',
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

        $company = Company::where('name', $request->company_name)->first();
        if(!$company) {
	        $company = Company::create([
	            'name' => $request->company_name
	        ]);
        }

		$password_string = md5(uniqid($request->email, true));
		// $password_string = 'password';
		
        $user = User::create([
            'name' => $request->administrator_name,
            'email' => $request->administrator_email,
            'password' => bcrypt($password_string),
            'company_id' => $company->id,
        ]);

		$user->profile->update([
			'address_1' => $request->address_1,
	        'zip' => $request->zip,
	        'state' => $request->state,
	        'city' => $request->city,
	        'country' => $request->country,
	        'phone' => $request->phone
		]);
		$user->roles()->attach(ROLE_CUSTOMER_ADMIN);

		$this->sendRegistrationMail($user, $password_string);
        // Mail::to($user->email)->send(new CustomerInvitation($password_string));

        return response()->json('Created successfully.');
    }

    public function getCustomer(Request $request, $id)
	{
		$customer = User::findOrFail($id);
		$customer->companyName = $customer->company->name;
		$profile = $customer->profile;
		$companies = Company::get();
		$cities = City::where('state', $profile->state)->orderBy('city')->get();
		
		return response()->json(compact('customer', 'profile', 'companies', 'cities'));
	}

	public function updateCustomerAccount(Request $request, $id)
	{
        $customer = User::findOrFail($id);
		$company = $customer->company;

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

        $profile = User::findOrFail($id)->profile;

        $profile->address_1 = $request->address_1;
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
		// $user = User::find(1);
		// $user->email = 'lasthyun822@gmail.com';
  //       $this->sendRegistrationMail($user, 'adsf');
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
