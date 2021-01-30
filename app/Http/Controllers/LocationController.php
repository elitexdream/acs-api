<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Location;

class LocationController extends Controller
{
	public function index(Request $request) {
        $locations = Location::get();

        return response()->json(compact('locations'));
	}

	public function store(Request $request) {
        $user = $request->user('api');

    	$validator = Validator::make($request->all(), [
	        'name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required'
	    ]);

	    if ($validator->fails())
	    {
            return response()->json(['error' => $validator->errors()], 422);            
        }

        $user->customerLocations()->create($request->all());

        return response()->json('Successfully created.');
    }

	public function update(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $location = Location::findOrFail($request->id);

        $location->name = $request->name;
        $location->state = $request->state;
        $location->city = $request->city;
        $location->zip = $request->zip;

        $location->save();

        return response()->json('Successfully updated.');
    }

    public function getMyLocations(Request $request) {
        $user = $request->user('api');

        $locations = $user->getMyLocations();

        return response()->json(compact('locations'));
    }
}
