<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \stdClass;

class ThresholdController extends Controller
{
    public function getTargetDevices(Request $request) {
        $user = $request->user('api');

        $response = new stdClass();

        $locations = $user->getMyLocations();

        foreach ($locations as $key => $location) {
            $zones = $location->zones();
            foreach ($zones as $key => $zone) {
                if($user->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
                    $devices = Device::where('location_id', $location)->where('zone_id', $zone)->get();
                } else {
                    $devices = $user->company->devices()->where('location_id', $location)->where('zone_id', $zone)->get();
                }

                $response[$location->id][$zone->id] = $devices;
            }
        }

        return response()->json(compact('response'));
    }
}
