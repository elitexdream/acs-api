<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Company;
use App\Machine;
use App\Imports\DevicesImport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class DeviceController extends Controller
{
	public function getDevices($pageNumber = 1) {
        $devices = Device::select('id', 'iccid', 'serial_number', 'registered', 'company_id', 'machine_id', 'sim_status')->paginate(7, ['*'], 'page', $pageNumber);
        $companies = Company::select('id', 'name')->get();
        $machines = Machine::select('id', 'name')->get();

        return response()->json([
            'devices' => $devices->items(),
            'companies' => $companies,
            'machines' => $machines,
            'last_page' => $devices->lastPage()
        ]);
	}

    public function uploadDevices(Request $request) {
    	$validator = Validator::make($request->all(), [ 
	        'devicesFile' => 'required|file',
	    ]);

	    if ($validator->fails())
	    {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

    	$existing_devices = Device::all();
    	$numAdded = 0;
    	$numDuplicates = 0;
    	$devices = Excel::toArray(new DevicesImport, $request->devicesFile);
        foreach ($devices[0] as $key => $device) {
        	if($key == 0)
        		continue;
        	if ($existing_devices->where('serial_number', $device[0])->count() > 0) {
        		$numDuplicates++;
        		continue;
        	}
        	Device::create([
	           'serial_number' => $device[0],
	           'imei' => $device[1], 
	           'lan_mac_address' => $device[2],
	           'iccid' => $device[3],
               'public_ip_sim' => $device[4],
               'machine_id' => null,
               'company_id' => null,
               'registered' => false
        	]);
        	$numAdded++;
        }

        return response()->json([
    		'numAdded' => $numAdded,
    		'numDuplicates' => $numDuplicates
        ]);
    }

    public function deviceAssigned(Request $request) {
        $device = Device::findOrFail($request->device_id);

        $device->company_id = $request->company_id;
        $device->machine_id = $request->machine_id;

        $device->save();

        return response()->json('Successfully assigned.');
    }

    public function updateRegistered(Request $request) {
        $device = Device::findOrFail($request->device_id);

        $device->registered = $request->register;

        $device->save();

        return response()->json('Successfully updated.');
    }
}