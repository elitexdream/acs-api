<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceData;

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
}
