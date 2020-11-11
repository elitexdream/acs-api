<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Imports\DevicesImport;
use Maatwebsite\Excel\Facades\Excel;

class DeviceController extends Controller
{
	public function getDevices() {
		return response()->json(Device::get(), 200);
	}

    public function uploadDevices(Request $request) {
    	$numAdded = 0;
    	$devices = Excel::toArray(new DevicesImport, $request->devicesFile);
        foreach ($devices[0] as $key => $device) {
        	if($key == 0)
        		continue;
        	Device::create([
	           'serial_number' => $device[0],
	           'imei' => $device[1], 
	           'lan_mac_address' => $device[2],
	           'iccid' => $device[3]
        	]);
        	$numAdded++;
        }


    	return response()->json([
    		'devices' => Device::get(),
    		'numAdded' => $numAdded,
    		'numDuplicates' => 0
    	], 200);
    }
}