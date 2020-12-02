<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;
use App\AlarmType;
use App\DeviceData;
use App\Machine;
use DB;

class MachineController extends Controller
{
	private $num_chunks = 16;

	public function index() {
    	$companies = Company::orderBy('name')->get();

		return response()->json(compact('companies'));
	}

	public function initProductPage(Request $request) {
		$id = $request->machineId;
		$machine = Machine::where('id', $id)->select('id', 'name')->first();

		// machine version
		if($version_object = DeviceData::where('machine_id', $id)->where('tag_id', 4)->latest('timestamp')->first()) {
			$machine->version = json_decode($version_object->values)[0];
		}

		if($id == MACHINE_BD_Batch_Blender) {
			$energy_consumption_values = DeviceData::where('machine_id', $id)
							->where('tag_id', 3)
							->pluck('values');
			$energy_consumption = $this->parseValid1($energy_consumption_values);

			if($request->mode === 'Monthly')
				$duration = strtotime("-1 month");
			else {
				$duration = strtotime("-1 week");
			}

			$targetValues = DB::table('device_data')
							->where('machine_id', $id)
							->where('tag_id', 13)
							->where('timestamp', '>', $duration)
							->orderBy('timestamp')
							->pluck('values');
			$actualValues = DB::table('device_data')
							->where('machine_id', $id)
							->where('tag_id', 14)
							->where('timestamp', '>', $duration)
							->orderBy('timestamp')
							->pluck('values');

			$hopValues = DB::table('device_data')
							->where('machine_id', $id)
							->where('tag_id', 15)
							->where('timestamp', '>', $duration)
							->orderBy('timestamp')
							->pluck('values');
			$frtValues = DB::table('device_data')
							->where('machine_id', $id)
							->where('tag_id', 16)
							->where('timestamp', '>', $duration)
							->orderBy('timestamp')
							->pluck('values');

			$targets = $this->parseValid($targetValues, $request->param);
			$actuals = $this->parseValid($actualValues, $request->param);
			$hops = $this->parseValid($hopValues, $request->param);
			$fractions = $this->parseValid($frtValues, $request->param);

			$alarm_types = AlarmType::where('machine_id', $id)->get();
			$alarms = DeviceData::where('machine_id', $id)
						->whereIn('tag_id', [27, 28, 31, 32, 33, 34, 35, 36, 37, 38])
						// ->where('timestamp', '>', $duration)
						->orderBy('timestamp')
						->get();

			return response()->json(compact('machine', 'energy_consumption', 'targets', 'actuals', 'hops', 'fractions', 'alarm_types', 'alarms'));
		} else if($id == MACHINE_GH_Gravimetric_Extrusion_Control_Hopper) {
			$energy_consumption_values = DeviceData::where('machine_id', $id)
							->where('tag_id', 3)
							->pluck('values');
			$energy_consumption = $this->parseValid1($energy_consumption_values);

			$hopper_inventory_values = DeviceData::where('machine_id', $id)
							->where('tag_id', 23)
							->orderBy('timestamp')
							->pluck('values');
			$hopper_inventories = $this->parseValid1($hopper_inventory_values);

			$hauloff_length_values = DeviceData::where('machine_id', $id)
							->where('tag_id', 24)
							->orderBy('timestamp')
							->pluck('values');
			$hauloff_lengths = $this->parseValid1($hauloff_length_values);

			$actual_points_values = DeviceData::where('machine_id', $id)
							->where('tag_id', 20)
							->pluck('values');
			$actual_points = $this->parseValid1($actual_points_values);

			$set_points_values = DeviceData::where('machine_id', $id)
							->where('tag_id', 21)
							->pluck('values');
			$set_points = $this->parseValid1($set_points_values);

			$alarm_types = AlarmType::where('machine_id', $id)->get();
			$alarms = DeviceData::where('machine_id', $id)
						->where('tag_id', 30)
						->orderBy('timestamp')
						->get();

			return response()->json(compact('machine', 'energy_consumption', 'hopper_inventories', 'hauloff_lengths', 'alarm_types', 'alarms', 'set_points', 'actual_points'));
		} else {
			return response()->json(compact('machine'));
		}
	}

	private function parseValid($raw_array, $i) {
		$width = $raw_array->count() / $this->num_chunks;
		$chunks = array_chunk(json_decode($raw_array), $width);
		return array_map(function($chunk) use ($i, $width) {
			$sum = 0; $count = 0;
			foreach ($chunk as $key => $item) {
				$sum += json_decode($item)[$i];
				$count ++;
			}

			return (int)($sum / $count);
		}, $chunks);
	}

	private function parseValid1($raw_array) {
		$width = $raw_array->count() / $this->num_chunks + 1;
		$chunks = array_chunk(json_decode($raw_array), $width);
		return array_map(function($chunk) use ($width) {
			$sum = 0; $count = 0;
			foreach ($chunk as $key => $item) {
				$tmp = json_decode($item)[0];
				$sum += $tmp;
				$count ++;
			}

			return (int)($sum / $count);
		}, $chunks);
	}

	public function getProductWeight(Request $request) {
		if($request->mode === 'Monthly')
			$duration = strtotime("-1 month");
		else {
			$duration = strtotime("-1 week");
		}

		$targetValues = DB::table('device_data')
						->where('machine_id', 1)
						->where('tag_id', 13)
						->where('timestamp', '>', $duration)
						->orderBy('timestamp')
						->pluck('values');
		$actualValues = DB::table('device_data')
						->where('machine_id', 1)
						->where('tag_id', 14)
						->where('timestamp', '>', $duration)
						->orderBy('timestamp')
						->pluck('values');

		$targets = $this->parseValid($targetValues, $request->param);
		$actuals = $this->parseValid($actualValues, $request->param);

		return response()->json(compact('targets', 'actuals'));
	}

	public function getProductInventory(Request $request) {
		if($request->mode === 'Monthly')
			$duration = strtotime("-1 month");
		else {
			$duration = strtotime("-1 week");
		}

		$hopValues = DB::table('device_data')
						->where('machine_id', 1)
						->where('tag_id', 15)
						->where('timestamp', '>', $duration)
						->orderBy('timestamp')
						->pluck('values');
		$frtValues = DB::table('device_data')
						->where('machine_id', 1)
						->where('tag_id', 16)
						->where('timestamp', '>', $duration)
						->orderBy('timestamp')
						->pluck('values');

		$hops = $this->parseValid($hopValues, $request->param);
		$fractions = $this->parseValid($frtValues, $request->param);

		return response()->json(compact('hops', 'fractions'));
	}
}
