<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Company;

class ACSAdminController extends Controller
{
	public function index()
	{
		$companies = Company::all();

		for ($i=0; $i < $companies->count(); $i++)
		{
			$companies[$i]->administratorName = $companies[$i]->customer()->name;
		}

		return response()->json([
			'companies' => $companies
		], 200);
	}

    public function addCustomer(Request $request)
	{
	    $validator = Validator::make($request->all(), [ 
	        'company_name' => 'required',
	        'administrator_name' => 'required',
	        'administrator_email' => 'required|email'
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

        return response()->json('Created successfully.', 200);
    }
}
