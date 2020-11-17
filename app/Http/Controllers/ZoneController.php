<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Zone;

class ZoneController extends Controller
{
    public function index() {
        $locations = Location::select('id', 'location')->get();
        $zones = Zone::select('id', 'zone_name', 'location_id')->get();

        return response()->json([
            'locations' => $locations,
            'zones' => $zones
        ]);
    }
}
