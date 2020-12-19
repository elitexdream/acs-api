<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Location;
use App\Zone;

class ZoneController extends Controller
{
    public function initLocationsAndZones(Request $request) {
        $user = $request->user('api');

        $locations = $user->customerLocations;
        $zones = $user->customerZones;

        return response()->json([
            'locations' => $locations,
            'zones' => $zones
        ]);
    }

    public function index(Request $request) {
        $user = $request->user('api');

        $zones = $user->getMyZones();
        
        return response()->json($zones);
    }

    public function store(Request $request) {
        $user = $request->user('api');

    	$validator = Validator::make($request->all(), [ 
	        'name' => 'required',
            'location_id' => 'required'
	    ]);

	    if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user->customerZones()->create($request->all());

        return response()->json('Successfully created.');
    }

    public function update(Request $request) {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'location_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $zone = Zone::findOrFail($request->id);

        $zone->name = $request->name;
        $zone->location_id = $request->location_id;
        $zone->save();

        return response()->json('Successfully updated.');
    }
}
