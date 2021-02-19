<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\MaterialLocation;

class MaterialLocationController extends Controller
{
    public function index() {
		$locations = MaterialLocation::get();

		return response()->json(compact('locations'));
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [ 
            'location' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

		MaterialLocation::create([
			'location' => $request->location,
		]);

		return response()->json('Created Successfully');
	}

	public function update(Request $request, $id) {
		$validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $location = MaterialLocation::findOrFail($id);

        $location->update([
        	'location' => $request->location,
        ]);

		return response()->json('Updated Successfully');
	}

	public function destroy($id) {
		$location = MaterialLocation::findOrFail($id);
		
		$location->delete();

		return response()->json('Deleted Successfully');
	}
}