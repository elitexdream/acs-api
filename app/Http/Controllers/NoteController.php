<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Machine;
use App\Note;

class NoteController extends Controller
{
	public function getMachineNotes($machine_id) {
		$machine = Machine::findOrFail($machine_id);

		$notes = $machine->notes;

	    return response()->json(compact('notes'));
	}

	public function store(Request $request) {

	    $validator = Validator::make($request->all(), [ 
	        'note' => 'required', 
	        'machineId' => 'required', 
	    ]);

	    if ($validator->fails()) {
	        return response()->json(['error'=>$validator->errors()], 422);            
	    }

	    Note::create([
	    	"note" => $request->note,
	    	"machine_id" => $request->machineId,
	    ]);

	    $notes = Note::get();

	    return response()->json(compact('notes'));
	}
}
