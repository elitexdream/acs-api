<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MachineTag;
use App\AlarmType;
use App\Device;

use \stdClass;

class MachineTagController extends Controller
{
    public function getMachineTags($machine_id) {
    	$tags = MachineTag::where('configuration_id', $machine_id)->orderBy('name')->get();
    	$alarm_tags = AlarmType::where('machine_id', $machine_id)->orderBy('name')->get();

    	$tags = $tags->merge($alarm_tags);

    	return response()->json(compact('tags'));
    }

	public function getMachinesTags(Request $request) {
		$device_ids = $request->deviceIds;
		$tags = [];
		foreach ($device_ids as $key => $device_id) {
			$device = Device::where('device_id', $device_id)->first();
			$machine_data = new stdClass();
			$machine_data->device_id = $device_id;

			$machine_tags = MachineTag::where('configuration_id', $device->machine_id)->orderBy('name')->get();
    		$alarm_tags = AlarmType::where('machine_id', $device->machine_id)->orderBy('name')->get();

    		$machine_tags = $machine_tags->merge($alarm_tags);
			$machine_data->tags = $machine_tags;

			array_push($tags, $machine_data);
		}
		return response()->json(compact('tags'));
	}
}
