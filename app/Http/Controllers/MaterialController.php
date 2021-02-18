<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Material;

class MaterialController extends Controller
{
    public function index() {
		$materials = Material::get();

		return response()->json(compact('materials'));
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [ 
            'material' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

		Material::create([
			'material' => $request->material,
			'location' => $request->location
		]);

		return response()->json('Created Successfully');
	}

	public function update(Request $request, Material $material) {
		$validator = Validator::make($request->all(), [ 
            'material' => 'required',
            'location' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $material->update([
        	'material' => $request->material,
        	'location' => $request->location
        ]);

		return response()->json('Updated Successfully');
	}

	public function destroy(Material $material) {
		$material->delete();

		return response()->json('Deleted Successfully');
	}
}
