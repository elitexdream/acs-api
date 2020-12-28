<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Machine;
use Validator;

class ConfigurationController extends Controller
{
    /*
		Get all configurations with full json
	*/
	public function getConfigurationNames() {
		$configurations = Machine::select('id', 'name')->get();

		return response()->json(compact('configurations'));
	}

	/*
		Get configuration details with full json
	*/
	public function getConfiguration($id) {
		$configuration = Machine::findOrFail($id);

		return response()->json(compact('configuration'));
	}

	public function saveConfiguration(Request $request, $id) {
		$validator = Validator::make($request->all(), [ 
            'name' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $configuration = Machine::findOrFail($id);

        $configuration->name = $request->name;
        if($request->new_json) {
        	$configuration->full_json = $request->new_json;
        }

		$configuration->save();

		return response()->json('Successfully updated');
	}
}
