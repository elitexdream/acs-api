<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Material;
use App\MaterialLocation;
use App\DeviceData;
use App\MaterialTrack;
use App\TeltonikaConfiguration;
use App\InventoryMaterial;
use App\Device;

use \stdClass;

class MaterialController extends Controller
{
    public function index(Request $request) {
    	$user = $request->user('api');

    	$company = $user->company;

		$materials = $company->materials;

		return response()->json(compact('materials'));
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [ 
            'material' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user = $request->user('api');

    	$company = $user->company;

		$company->materials()->create([
			'material' => $request->material,
		]);

		return response()->json('Created Successfully');
	}

	public function update(Request $request, Material $material) {
		$validator = Validator::make($request->all(), [ 
            'material' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $material->update([
        	'material' => $request->material,
        ]);

		return response()->json('Updated Successfully');
	}

	public function destroy(Material $material) {
		$material->delete();

		return response()->json('Deleted Successfully');
	}

    public function getBlenders(Request $request) {
        $user = $request->user('api');
        $company = $user->company;
        $inventory_materials = $company->inventoryMaterials();
        $inventory_material_plc_ids = $inventory_materials->pluck('plc_id');

        $teltonika_ids = TeltonikaConfiguration::whereIn('plc_serial_number', $inventory_material_plc_ids)->pluck('teltonika_id');

        $blenders = $company->devices()->select('customer_assigned_name', 'id', 'serial_number')->whereIn('serial_number', $teltonika_ids)->get();

        return response()->json(compact('blenders'));
    }

    public function getMaterialReport(Request $request) {
        $device = Device::where('serial_number', $request->blenderId)->first();
        $serial_number = $device->teltonikaConfiguration->plcSerialNumber();
        $inventoryMaterial = InventoryMaterial::where('plc_id', $serial_number)->first();
        $tracks = $inventoryMaterial->materialTracks;

        $materials = Material::get();
        $locations = MaterialLocation::get();

        $material_keys = ['material1_id', 'material2_id', 'material3_id', 'material4_id', 'material5_id', 'material6_id', 'material7_id', 'material8_id'];
        $location_keys = ['location1_id', 'location2_id', 'location3_id', 'location4_id', 'location5_id', 'location6_id', 'location7_id', 'location8_id'];

        foreach ($tracks as $key => &$track) {
            $hop_materials = DeviceData::where('serial_number', $serial_number)
                ->where('tag_id', 15)
                ->where('timestamp', '>', $track->start)
                ->where('timestamp', '<', $track->stop)
                ->get();

            $actual_materials = DeviceData::where('serial_number', $serial_number)
                ->where('tag_id', 16)
                ->where('timestamp', '>', $track->start)
                ->where('timestamp', '<', $track->stop)
                ->get();

            $initial_materials = (array)json_decode($track->initial_materials);

            $reportItems = [];

            for ($i=0; $i < 8; $i++) {
                $material_id = $initial_materials[$material_keys[$i]];
                $location_id = $initial_materials[$location_keys[$i]];

                if ($material_id) {
                    $item = new stdClass();

                    $item->value = 0;

                    $material = $materials->where('id', $material_id)->first();
                    $item->material = $material ? $material->material : '';

                    $location = $locations->where('id', $location_id)->first();
                    $item->location = $location ? $location->location : '';

                    foreach ($hop_materials as $hop_material) {
                        $actual_material = $actual_materials->where('timestamp', $hop_material->timestamp)->first();
                        $item->value += json_decode($hop_material->values)[$i] + json_decode($actual_material->values)[$i] / 1000;
                    }

                    $item->value = round($item->value, 3);
                    array_push($reportItems, $item);
                }
            }
            
            $track->reportItems = $reportItems;
        }

        return response()->json(compact('tracks'));
    }
}
