<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomerInvitation;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\User;
use App\Company;
use App\Profile;

class CustomerController extends Controller
{
	public function index()
	{
		$companies = Company::select('id', 'name', 'user_id')->get();

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
	        'administrator_email' => 'required|email|max:255|unique:users,email'
	    ]);

	    if ($validator->fails())
	    {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user = User::create([
            'name' => $request->administrator_name,
            'email' => $request->administrator_email,
            'password' => bcrypt('password'),
        ]);

		$user->roles()->attach(3);

		$company = Company::create([
            'user_id' => $user->id,
            'name' => $request->company_name
        ]);

		$profile = new Profile();

        $user->profile()->save($profile);

        Mail::to($user->email)->send(new CustomerInvitation());

        return response()->json('Created successfully.', 201);
    }

    public function getCustomer(Request $request, $id)
	{
		$company = Company::findOrFail($id);
		$company->administratorName = $company->customer->name;
		$company->administratorEmail = $company->customer->email;
		$profile = $company->customer->profile;
		$profile->id = $id;
		
		return response()->json([
			'company' => $company,
			'profile' => $profile,
		], 200);
	}

	public function updateCustomerAccount(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [ 
	        'name' => 'required',
	        'administrator_name' => 'required',
	        'administrator_email' => 'required|email|max:255'
	    ]);

	    if ($validator->fails())
	    {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $company = Company::findOrFail($id);
        $customer = $company->customer;

        $company->name = $request->name;
        $customer->name = $request->administrator_name;
        $customer->email = $request->administrator_email;

        $company->save();
        $customer->save();

        return response()->json([
			'message' => 'Updated Successfully.'
		], 200);
	}

	public function updateCustomerProfile(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
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

        return response()->json([
			'message' => 'Updated Successfully.'
		], 200);
	}
}
