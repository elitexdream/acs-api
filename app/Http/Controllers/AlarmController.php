<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceData;
use App\AlarmType;

class AlarmController extends Controller
{
    public function getProductAlarms(Request $request) {
		$alarms = DeviceData::where('machine_id', 1)
						->where('tag_id', 27)
						->orderBy('timestamp')
						->where('timestamp', '>', strtotime($request->from))
						->where('timestamp', '<', strtotime($request->to))
						->get();

		return response()->json(compact('alarms'));
	}

	public function getCorrespondingAlarmTypes(Request $request, $id) {
		$alarm_types = AlarmType::select('name')
							->where('machine_id', $id)
							->get();

		return response()->json(compact('alarm_types'));
	}
}
