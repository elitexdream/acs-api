<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Location;
use App\Zone;

class ZoneController extends Controller
{
    public function initLocationsAndZones() {
        $locations = Location::get();
        $zones = Zone::select('id', 'name', 'location_id')->get();

        return response()->json([
            'locations' => $locations,
            'zones' => $zones
        ]);
    }

    public function index() {
        $zones = Zone::select('id', 'name', 'location_id')->get();

        return response()->json($zones);
    }

    public function store(Request $request) {
    	$validator = Validator::make($request->all(), [ 
	        'name' => 'required',
            'location_id' => 'required'
	    ]);

	    if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }
        $zone = Zone::create([
            'name' => $request->name,
            'location_id' => $request->location_id
        ]);

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
