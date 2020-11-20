<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomerInvitation;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\User;
use App\Company;
use App\Profile;

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

        // Mail::to($user->email)->send(new CustomerInvitation());

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

	public function testMail()
	{
		$email = new \SendGrid\Mail\Mail();
		$email->setFrom("al@machinecdn.com", "ACS");
		$email->setSubject("Sending with Twilio SendGrid is Fun");
		$email->addTo("ahmadyasser7@outlook.com", "Example User");
		$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
		$email->addContent(
		    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
		);
		$sendgrid = new \SendGrid(env('SENDGRID_API'));
		try {
		    $response = $sendgrid->send($email);
		    print $response->statusCode() . "\n";
		    print_r($response->headers());
		    print $response->body() . "\n";
		} catch (Exception $e) {
		    echo 'Caught exception: '. $e->getMessage() ."\n";
		}
		// Mail::to('ahmadyasser7@outlook.com')->send(new CustomerInvitation());
	}
}
