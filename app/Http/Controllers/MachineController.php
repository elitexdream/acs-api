<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;
use App\AlarmType;
use App\DeviceData;
use DB;

class MachineController extends Controller
{
	private $num_chunks = 16;

	public function index() {
    	$companies = Company::orderBy('name')->get();

		return response()->json(compact('companies'));
	}

	public function initProductPage(Request $request) {
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

		$targets = $this->parseValid($targetValues, $request->param);
		$actuals = $this->parseValid($actualValues, $request->param);
		$hops = $this->parseValid($hopValues, $request->param);
		$fractions = $this->parseValid($frtValues, $request->param);

		$alarm_types = AlarmType::get();
		$alarms = DeviceData::where('machine_id', 6)
					->whereIn('tag_id', [27, 28, 31, 32, 33, 34, 35, 36, 37, 38])
					// ->where('timestamp', '>', $duration)
					->orderBy('timestamp')
					->get();

		return response()->json(compact('targets', 'actuals', 'hops', 'fractions', 'alarm_types', 'alarms'));
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
