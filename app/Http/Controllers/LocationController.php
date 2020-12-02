<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Location;

class LocationController extends Controller
{
	public function index() {
		$locations = Location::get();

		return response()->json($locations);
	}

	public function store(Request $request) {
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

        Location::create($request->all());

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
}
