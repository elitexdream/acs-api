<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Machine;
use App\Note;
use App\Device;

class NoteController extends Controller
{
	/*
		Get notes for specified device
		params: device id
		return: All notes belongs to specified device
	*/
	public function getNotes($device_id) {
		$device = Device::where('serial_number', $device_id)->first();

	    if(!$device) {
			return response()->json('Device Not Found', 404);
	    }

		$notes = $device->notes;

	    return response()->json(compact('notes'));
	}

	/*
		Add note
		params: deviceId
				note string
	*/
	public function store(Request $request) {

	    $validator = Validator::make($request->all(), [ 
	        'note' => 'required', 
	        'deviceId' => 'required', 
	    ]);

	    if ($validator->fails()) {
	        return response()->json(['error'=>$validator->errors()], 422);
	    }

	    $device = Device::where('serial_number', $request->deviceId)->first();

	    if(!$device) {
			return response()->json('Device Not Found', 404);
	    }

	    Note::create([
	    	"note" => $request->note,
	    	"device_id" => $device->id,
	    ]);

	    $notes = Note::get();

	    return response()->json('Created successfully');
	}
}
