<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\MaterialLocation;

class MaterialLocationController extends Controller
{
    public function index(Request $request) {
		$user = $request->user('api');

    	$company = $user->company;

		$locations = $company->materialLocations;

		return response()->json(compact('locations'));
	}

	public function store(Request $request) {
		$user = $request->user('api');

    	$company = $user->company;

		$validator = Validator::make($request->all(), [ 
            'location' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $company->materialLocations()->create([
			'location' => $request->location,
		]);

		return response()->json('Created Successfully');
	}

	public function update(Request $request, $id) {
		$validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $location = MaterialLocation::findOrFail($id);

        $location->update([
        	'location' => $request->location,
        ]);

		return response()->json('Updated Successfully');
	}

	public function destroy($id) {
		$location = MaterialLocation::findOrFail($id);
		
		$location->delete();

		return response()->json('Deleted Successfully');
	}
}