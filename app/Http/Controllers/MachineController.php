<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;
use App\AlarmType;
use App\DeviceData;
use App\Machine;
use App\Location;
use App\Zone;
use App\Device;
use App\TeltonikaConfiguration;
use App\DowntimePlan;
use App\Tag;
use App\InventoryMaterial;
use App\SystemInventory;
use App\MachineTag;

use DB;
use \stdClass;
use Carbon\Carbon;

class MachineController extends Controller
{
	private $num_chunks = 12;
	private $timeshift = 0;

    public function __construct()
    {
    	$user = auth('api')->user();
		$timezone = DB::Table('timezones')->where('id', $user->profile->timezone)->first();
		if($timezone) {
			date_default_timezone_set($timezone->name);

			$this->timeshift = date('Z');
		}
    }

    public function averagedSeries($collection, $series_count = 200, $devide_by = 1) {
    	$total = $collection->count();
		$chunks = $collection->chunk($total / $series_count + 1);

		$ret = $chunks->map(function($chunk) use ($devide_by) {
			$timestamp = ($chunk->first()->timestamp + $this->timeshift) * 1000;
			$values = $chunk->map(function($value) use ($devide_by) {
				return json_decode($value->values)[0] / $devide_by;
			});
			return [$timestamp, round(array_sum($values->all()) / $chunk->count(), 2)];
		});

		return $ret;
    }

    public function isImperial($machineId, $serialNumber, $tag_id = 0) {
    	$isImperial = false;

    	if (!$tag_id) {
	    	switch ($machineId) {
	    		case MACHINE_BD_BATCH_BLENDER:
	    			$tag_id = 51;
	    			break;
	    		
	    		default:
	    			break;
	    	}
	    }

		$unit = DeviceData::where('serial_number', $serialNumber)->where('tag_id', $tag_id)->latest('timedata')->first();

		if($unit)
			$isImperial = !json_decode($unit->values)[0];

		return $isImperial;
    }

    public function isPlcRunning($machineId, $serialNumber) {
		$tag = Tag::where('configuration_id', $machineId)
            ->where('tag_name', Tag::NAMES['RUNNING'])
            ->first();

        if ($tag) {
            $running = DB::table('runnings')
                ->where('serial_number', $serialNumber)
                ->where('tag_id', $tag->tag_id)
                ->latest('timestamp')
                ->first();

            if ($running) {
                return json_decode($running->values)[0];
            }
        }

        return false;
    }
	/*
		Get general information of product
		They are Name, Serial number, Software build, and version
		return: Object
	*/
	public function getProductOverview(Request $request) {
		$product = new stdClass();

		if($request->machineId == MACHINE_TRUETEMP_TCU) {
			if($version_object = DeviceData::where('serial_number', $request->serialNumber)
								->where('tag_id', 1)
								->latest('timestamp')
								->first()) {
				try {
					$product->version = json_decode($version_object->values)[0];
				} catch (\Exception $e) {
					$product->version = '';
				}
			}
		} else {
			if($request->machineId == MACHINE_HE_CENTRAL_CHILLER) {
				switch ($request->circuitId) {
					case 1:
						$tag_software_version = 264;
						break;

					case 2:
						$tag_software_version = 265;
						break;
						
					case 3:
						$tag_software_version = 266;
						break;
						
					case 4:
						$tag_software_version = 267;
						break;
						
					case 5:
						$tag_software_version = 268;
						break;
						
					case 6:
						$tag_software_version = 269;
						break;
						
					case 7:
						$tag_software_version = 270;
						break;
						
					case 8:
						$tag_software_version = 271;
						break;

					case 9:
						$tag_software_version = 272;
						break;

					case 10:
						$tag_software_version = 273;
						break;

					default:
						$tag_software_version = 264;
						break;
				}

				if(!$tag_software_version) {
					return response()->json('Software version tag not found', 404);
				}

				// product version
				if($version_object = DB::table('device_data')
									->where('serial_number', $request->serialNumber)
									->where('tag_id', $tag_software_version)
									->latest('timestamp')
									->first()) {
					try {
						$product->version = json_decode($version_object->values)[0] / 10;
					} catch (\Exception $e) {
						$product->version = '';
					}
				}
			} else {
				$tag_software_version = Tag::where('tag_name', 'software_version')->where('configuration_id', $request->machineId)->first();
				if(!$tag_software_version) {
					return response()->json('Software version tag not found', 404);
				}

				// product version
				if($version_object = DB::table('software_version')
									->where('serial_number', $request->serialNumber)
									->where('tag_id', $tag_software_version->tag_id)
									->latest('timestamp')
									->first()) {
					try {
						$product->version = json_decode($version_object->values)[0] / 10;
					} catch (\Exception $e) {
						$product->version = '';
					}
				}
			}

			// software build
			$tag_software_build = Tag::where('tag_name', 'software_build')->where('configuration_id', $request->machineId)->first();

			if($tag_software_build) {
				if($software_build_object = DB::table('software_builds')
												->where('serial_number', $request->serialNumber)
												->where('tag_id', $tag_software_build->tag_id)
												->latest('timestamp')
												->first()) {
					if ($software_build_object)
						$product->software_build = sprintf('%03d', json_decode($software_build_object->values)[0]);
					else
						$product->software_build = '';
				}
			}

			// serial number
			$serial_year = '';
			$serial_month = '';
			$serial_unit = '';

			if($request->machineId == MACHINE_HE_CENTRAL_CHILLER) {
				$circuit_id = $request->circuitId || 1;

				$tag_serial_year = Tag::where('tag_name', 'serial_number_year_' . $circuit_id)->where('configuration_id', $request->machineId)->first();
				$tag_serial_month = Tag::where('tag_name', 'serial_number_month_' . $circuit_id)->where('configuration_id', $request->machineId)->first();
				$tag_serial_unit = Tag::where('tag_name', 'serial_number_unit_' . $circuit_id)->where('configuration_id', $request->machineId)->first();

				if($tag_serial_year) {
					$serial_year_object = DeviceData::where('serial_number', $request->serialNumber)
												->where('tag_id', $tag_serial_year->tag_id)
												->latest('timestamp')
												->first();
					try {
						$serial_year = json_decode($serial_year_object->values)[0];
					} catch (\Exception $e) {
						$serial_year = '';
					}
				}

				if($tag_serial_month) {
					$serial_month_object = DeviceData::where('serial_number', $request->serialNumber)
												->where('tag_id', $tag_serial_month->tag_id)
												->latest('timestamp')
												->first();
					try {
						$serial_month = chr(json_decode($serial_month_object->values)[0] + 65);
					} catch (\Exception $e) {
						$serial_month = '';
					}
				}

				if($tag_serial_unit) {
					$serial_unit_object = DeviceData::where('serial_number', $request->serialNumber)
												->where('tag_id', $tag_serial_unit->tag_id)
												->latest('timestamp')
												->first();
					try {
						$serial_unit = sprintf('%04d', json_decode($serial_unit_object->values)[0]);
					} catch (\Exception $e) {
						$serial_unit = '';
					}
				}
			} else {
				$tag_serial_year = Tag::where('tag_name', 'serial_number_year')->where('configuration_id', $request->machineId)->first();
				$tag_serial_month = Tag::where('tag_name', 'serial_number_month')->where('configuration_id', $request->machineId)->first();
				$tag_serial_unit = Tag::where('tag_name', 'serial_number_unit')->where('configuration_id', $request->machineId)->first();

				if($tag_serial_year) {
					$serial_year_object = DB::table('serial_number_year')
												->where('serial_number', $request->serialNumber)
												->where('tag_id', $tag_serial_year->tag_id)
												->latest('timestamp')
												->first();
					try {
						$serial_year = json_decode($serial_year_object->values)[0];
					} catch (\Exception $e) {
						$serial_year = '';
					}
				}

				if($tag_serial_month) {
					$serial_month_object = DB::table('serial_number_month')
												->where('serial_number', $request->serialNumber)
												->where('tag_id', $tag_serial_month->tag_id)
												->latest('timestamp')
												->first();
					try {
						$serial_month = chr(json_decode($serial_month_object->values)[0] + 65);
					} catch (\Exception $e) {
						$serial_month = '';
					}
				}

				if($tag_serial_unit) {
					$serial_unit_object = DB::table('serial_number_unit')
											->where('serial_number', $request->serialNumber)
											->where('tag_id', $tag_serial_unit->tag_id)
											->latest('timestamp')
											->first();
					if ($serial_unit_object) {
						$serial_unit = sprintf('%04d', json_decode($serial_unit_object->values)[0]);
					}
					else
						$serial_unit = '';
				}
			}

			$product->serial = mb_convert_encoding($serial_year . $serial_month . $serial_unit, 'UTF-8', 'UTF-8');
		}

		$machine = Machine::findOrFail($request->machineId);

		$product->machineName = $machine->name;
		$product->machineId = $machine->id;

		$configuration = TeltonikaConfiguration::where('plc_serial_number', $request->serialNumber)->first();

		if ($configuration) {
			$product->teltonikaDevice = Device::where('serial_number', $configuration->teltonika_id)->first();
		}

		if ($request->machineId == MACHINE_VTC_PLUS_CONVEYING_SYSTEM) {
			$plus_model = '';

			$plus_model_object = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 9)->latest('timedata')->first();

			if ($plus_model_object) {
				$plus_model = json_decode($plus_model_object->values)[0];
				$product->teltonikaDevice->name = $product->teltonikaDevice->name . ' (VTC Plus' . $plus_model . ')';
			}
		}

		$product->running = $this->isPlcRunning($request->machineId, $request->serialNumber);

		return response()->json([
			"overview" => $product
		]);
	}

	/*
		Get inventories BD_Batch_Blender
		L30_0_8_HopInv and L30_16_23_FracInv are grouped together
		return: array
	*/
	public function getInventories(Request $request) {
		$hop_inventory = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 15)
									->latest('timedata')
									->first();
		$actual_inventory = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 16)
									->latest('timedata')
									->first();

		$inventories = [];

		if($hop_inventory && $actual_inventory) {
			$inventory_values = json_decode($actual_inventory->values);
			for($i = 0; $i < count($inventory_values); $i ++) {
				if (!$inventory_values[$i]) {
					$inv2 = sprintf('%01d', $inventory_values[$i]);
				} else {
					$inv2 = sprintf('%03d', $inventory_values[$i]);
				}
				$inv = strval(json_decode($hop_inventory->values)[$i]) . '.' . $inv2;
				array_push($inventories, $inv);
			}
		} else {
			$inventories = [];
		}

		$isImperial = $this->isImperial(MACHINE_BD_BATCH_BLENDER, $request->serialNumber, 50);

		$inventory_material = InventoryMaterial::where('plc_id', $request->serialNumber)->first();

		if(!$inventory_material)
			$inventory_material = InventoryMaterial::create([
				'plc_id' => $request->serialNumber
			]);

		$inventory_material->in_progress = $inventory_material->isInProgress();

		return response()->json([
			'data' => [
				'inventories' => $inventories,
				'inventory_material' => $inventory_material,
				'unit' => $isImperial ? 'lbs' : 'kgs'
			]
		]);
	}

	public function updateInventoryMaterial(Request $request) {
		$user = $request->user('api');

		$inventory_material = InventoryMaterial::where('plc_id', $request->serialNumber)->first();

		if(!$inventory_material)
			$inventory_material = InventoryMaterial::create([
				'plc_id' => $request->serialNumber,
				'company_id' => $user->company->id
			]);

		switch($request->id) {
		case 0:
			$inventory_material->update([
				'material1_id' => $request->material,
				'location1_id' => $request->location
			]);
			break;
		case 1:
			$inventory_material->update([
				'material2_id' => $request->material,
				'location2_id' => $request->location
			]);
			break;
		case 2:
			$inventory_material->update([
				'material3_id' => $request->material,
				'location3_id' => $request->location
			]);
			break;
		case 3:
			$inventory_material->update([
				'material4_id' => $request->material,
				'location4_id' => $request->location
			]);
			break;
		case 4:
			$inventory_material->update([
				'material5_id' => $request->material,
				'location5_id' => $request->location
			]);
			break;
		case 5:
			$inventory_material->update([
				'material6_id' => $request->material,
				'location6_id' => $request->location
			]);
			break;
		case 6:
			$inventory_material->update([
				'material7_id' => $request->material,
				'location7_id' => $request->location
			]);
			break;
		case 7:
			$inventory_material->update([
				'material8_id' => $request->material,
				'location8_id' => $request->location
			]);
			break;
		default:
			break;
		}

		$hop_material = DeviceData::where('serial_number', $request->serialNumber)
            ->where('tag_id', 15)
            ->latest('timedata')
            ->first();

        $actual_material = DeviceData::where('serial_number', $request->serialNumber)
            ->where('tag_id', 16)
            ->latest('timedata')
            ->first();

        $inventory = json_decode($hop_material->values)[$request->id] + json_decode($actual_material->values)[$request->id] / 1000;

		SystemInventory::create([
			'hopper_id' => $request->id,
			'material_id' => $request->material,
			'location_id' => $request->location,
			'inventory' => $inventory,
			'serial_number' => $request->serialNumber,
			'company_id' => $user->company->id
		]);

		return response()->json('Updated Successfully');
	}

	public function updateTrackingStatus(Request $request) {
		$user = $request->user('api');

		$inventory_material = InventoryMaterial::where('plc_id', $request->serialNumber)->first();

		if(!$inventory_material)
			$inventory_material = InventoryMaterial::create([
				'plc_id' => $request->serialNumber,
				'company_id' => $user->company->id
			]);

		if ($inventory_material->isInProgress()) {
			$inventory_material->materialTracks()->latest('start')->first()->update([
				'stop' => Carbon::now()->timestamp,
				'in_progress' => false
			]);

			return response()->json([
				'in_progress' => false
			]);
		} else {
			$inventory_material->materialTracks()->create([
				'start' => Carbon::now()->timestamp,
				'in_progress' => true,
				'initial_materials' => json_encode($inventory_material)
			]);

			return response()->json([
				'in_progress' => true
			]);
		}
	}
	/*
		configuration: BD Blender configuration
		description: feeder calibration factors
		tag: L21_0_11_RecipeVal
	*/
	public function getCurrentRecipe(Request $request) {
		$mode = 0;
		$recipe_values = [];
		$ez_types = [0, 0, 0, 0, 0, 0, 0, 0];

		$mode_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 45)
						->latest('timedata')
						->first();

		if($mode_object) {
			$mode = json_decode($mode_object->values)[0];

			$last_object = DeviceData::where('serial_number', $request->serialNumber)
							->where('tag_id', 47)
							->latest('timedata')
							->first();

			if( $last_object) {
				$recipe_values = json_decode($last_object->values);

				$recipe_values = array_map(function($i) {
					return $i / 100;
				}, $recipe_values);
			}

			if($mode == 2) {
				$last_object = DeviceData::where('serial_number', $request->serialNumber)
								->where('tag_id', 46)
								->latest('timedata')
								->first();

				if( $last_object) {
					$ez_types = json_decode($last_object->values);
				}
			}
		}

		return response()->json([
			'mode' => $mode,
			'recipe_values' => $recipe_values,
			'ez_types' => $ez_types
		]);
	}

	/*
		actual and target weight in BD_Batch_Blender
	*/
	public function getProductWeight(Request $request) {
		$actual_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 13)
						->whereJsonLength('values', 8)
						->latest('timedata')
						->first();

		$target_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 14)
						->whereJsonLength('values', 8)
						->latest('timedata')
						->first();

		$targets = [];
		$actuals = [];

		if($target_object) {
			$targets = json_decode($target_object->values);
			foreach ($targets as &$target) {
				$target = $target / 1000;
			}
		}

		if($actual_object) {
			$actuals = json_decode($actual_object->values);
			foreach ($actuals as &$actual) {
				$actual = round($actual / 1000, 3);
			}
		}

		$isImperial = $this->isImperial(MACHINE_BD_BATCH_BLENDER, $request->serialNumber, 51);

		$items = [$actuals, $targets];
		return response()->json([
			'items' => $items,
			'unit' => $isImperial ? 'lbs' : 'kgs'
		]);
	}

	/*
		configuration: BD Blender configuration
		description: get station conveying series
	*/
	public function getStationConveyings(Request $request) {
		$last_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 26)
						->latest('timedata')
						->first();

		if( $last_object) {
			$items = json_decode($last_object->values);
		} else {
			$items = [];
		}

		return response()->json(compact('items'));
	}

	public function getLoadCells(Request $request) {
		$loadCells = [];

		$tag_ids = [11, 12, 20, 21, 22, 23];
		$names = ['Batch Size', 'Batch Counter', 'Load cell A zero bits', 'Load cell A cal bits', 'Load cell B zero bits', 'Load cell B cal bits'];

		for ($i=0; $i < 6; $i++) { 
			$last_object = DeviceData::where('serial_number', $request->serialNumber)
							->where('tag_id', $tag_ids[$i])
							->latest('timedata')
							->first();

			$item = new stdClass();
			$item->name = $names[$i];
			$item->value = 0;

			if( $last_object) {
				if ($i === 0) {
					$item->value = sprintf('%01.1f', json_decode($last_object->values)[0] / 10);
				} else {
					$item->value = json_decode($last_object->values)[0];
				}
			}

			array_push($loadCells, $item);
		}

		return response()->json(compact('loadCells'));
	}

	/*
		configuration: BD Blender configuration
		description: get hopper stables
		tag: B3_17_8_15_FIFOStable
	*/
	public function getHopperStables(Request $request) {
		$last_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 25)
						->latest('timedata')
						->first();

		if( $last_object) {
			$stables = json_decode($last_object->values);
		} else {
			$stables = [];
		}

		return response()->json(compact('stables'));
	}

	/*
		configuration: BD Blender configuration
		description: feeder calibration factors
		tag: L51_0_8_AvgFeedCal
	*/
	public function getBDBlenderCalibrationFactors(Request $request) {
		$calibration_factors = [];

		$isImperial = $this->isImperial(MACHINE_BD_BATCH_BLENDER, $request->serialNumber);

		$factors_object = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 19)
									->latest('timedata')
									->first();

		if($factors_object) {
			$calibration_factors = json_decode($factors_object->values);
			foreach ($calibration_factors as &$factor) {
				$factor = $factor / 100;
			}
		}

		$items = $calibration_factors;
		return response()->json(compact('items'));
	}

	/*
		configuration: BD Blender configuration
		description: process rate, 1 point, L30_30_0_average_pr (lbs/hr or kgs/hr) DINT 
		tag: L30_30_0_average_pr
	*/
	public function getBlenderProcessRate(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$isImperial = $this->isImperial(MACHINE_BD_BATCH_BLENDER, $request->serialNumber);

		$process_rates_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 18)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timestamp')
										->get();

		$process_rate = $this->averagedSeries($process_rates_object);

		$items = [$process_rate];
		return response()->json(compact('items', 'isImperial'));
	}

	/*
		Get product utilization series
		return: Utilization Series Array
	*/
	public function getProductUtilization(Request $request) {
		$tag_utilization = Tag::where('tag_name', 'capacity_utilization')->where('configuration_id', $request->machineId)->first();

		if(!$tag_utilization) {
			return response()->json('Capacity utilization tag not found', 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$utilizations_object = DB::table('utilizations')
								->where('serial_number', $request->serialNumber)
								->where('tag_id', $tag_utilization->tag_id)
								->where('timestamp', '>', $from)
								->where('timestamp', '<', $to)
								->orderBy('timestamp')
								->get();

		$utilizations = $this->averagedSeries($utilizations_object, 200, 10);

		$items = [$utilizations];

		return response()->json(compact('items'));
	}

	/*
		Get energy consumption series
		return: Energy consumption series array
	*/
	public function getEnergyConsumption(Request $request) {
		$tag_energy_consumption = Tag::where('tag_name', 'energy_consumption')->where('configuration_id', $request->machineId)->first();

		if(!$tag_energy_consumption) {
			return response()->json('Energy consumption tag not found', 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$energy_consumptions_object = DB::table('energy_consumptions')
										->where('serial_number', $request->serialNumber)
										->where('tag_id', $tag_energy_consumption->tag_id)
										->orderBy('timestamp')
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->get();

		$energy_consumption = $this->averagedSeries($energy_consumptions_object, 200, 10);

		$items = [$energy_consumption];
		
		return response()->json(compact('items'));
	}

	/*
		Get Target recipe and actual recipe in Accumeter Ovation Continuous Blender configuration
		params: device_id
		return: Actual and target recipes
	*/
	public function getTgtActualRecipes(Request $request) {
		$targets = [];
		$actuals = [];

		$target_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 11)
						->latest('timedata')
						->first();

		if($target_object) {
			$targets = json_decode($target_object->values);

			$actual_recipe_object1 = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 12)
									->where('timestamp', $target_object->timestamp)
									->first();
			if($actual_recipe_object1) {
				$actuals[0] = round(json_decode($actual_recipe_object1->values)[0], 2);
			}

			$actual_recipe_object2 = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 13)
									->where('timestamp', $target_object->timestamp)
									->first();
			if($actual_recipe_object2) {
				$actuals[1] = round(json_decode($actual_recipe_object2->values)[0], 2);
			}

			$actual_recipe_object3 = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 14)
									->where('timestamp', $target_object->timestamp)
									->first();
			if($actual_recipe_object3) {
				$actuals[2] = round(json_decode($actual_recipe_object3->values)[0], 2);
			}

			$actual_recipe_object4 = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 15)
									->where('timestamp', $target_object->timestamp)
									->first();
			if($actual_recipe_object4) {
				$actuals[3] = round(json_decode($actual_recipe_object4->values)[0], 2);
			}

			$actual_recipe_object5 = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 16)
									->where('timestamp', $target_object->timestamp)
									->first();
			if($actual_recipe_object5) {
				$actuals[4] = round(json_decode($actual_recipe_object5->values)[0], 2);
			}

			$actual_recipe_object6 = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', 17)
									->where('timestamp', $target_object->timestamp)
									->first();
			if($actual_recipe_object6) {
				$actuals[5] = round(json_decode($actual_recipe_object6->values)[0], 2);
			}
		}

		$items = [$actuals, $targets];

		return response()->json(compact('items'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender configuration
		description: Get Feeder stables
	*/
	public function getFeederStables(Request $request) {
		$feeders_object = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 26)->latest('timedata')->first();

		if($feeders_object) {
			$feeders = json_decode($feeders_object->values);
		} else {
			$feeders = [];
		}

		return response()->json(compact('feeders'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender configuration
		description: Get product production rate
		return: Rate Series Array
	*/
	public function getProductProcessRate(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$isImperial = false;

		$unit = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 56)->latest('timedata')->first();

		if($unit)
			$isImperial = json_decode($unit->values)[0];

		$process_rates_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 23)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->latest('timedata')
										->get();

		$process_rate = $this->averagedSeries($process_rates_object);

		$items = [$process_rate];

		return response()->json(compact('items', 'isImperial'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender configuration
		description: Get Machine state, system steady, mass flow hopper and RPM
	*/
	public function getProductStates(Request $request) {
		$machine_states = new stdClass();

		if($request->machineId == MACHINE_TRUETEMP_TCU) {
			$machine_states->pump_status = 0;
			$machine_states->heater_status = 0;
			$machine_states->vent_status = 0;
			
			if($pump_status_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 40)
						->latest('timedata')
						->first()) {
				try {
					$machine_states->pump_status = json_decode($pump_status_object->values)[0];
				} catch(Exception $e) {
					$machine_states->pump_status = 0;
				}
			}

			if($heater_status_object = DeviceData::where('serial_number', $request->serialNumber)
				->where('tag_id', 41)
				->latest('timedata')
				->first()) {
				try {
					$machine_states->heater_status = json_decode($heater_status_object->values)[0];
				} catch(Exception $e) {
					$machine_states->heater_status = 0;
				}
			}

			if($vent_status_object = DeviceData::where('serial_number', $request->serialNumber)
				->where('tag_id', 42)
				->latest('timedata')
				->first()) {
				try {
					$machine_states->vent_status = json_decode($vent_status_object->values)[0];
				} catch(Exception $e) {
					$machine_states->vent_status = 0;
				}
			}
		} else {
			$machine_states->machine_running = false;
			$machine_states->system_steady = false;
			$machine_states->mass_flow_hopper = false;
			$machine_states->rpm = false;

			$states_object = DeviceData::where('serial_number', $request->serialNumber)
				->whereIn('tag_id', [10, 24, 25, 27])
				->latest('timedata')
				->get()
				->unique('tag_id');

			if($states_object) {
				$machine_running = $states_object->firstWhere('tag_id', 10);
				if($machine_running && json_decode($machine_running->values)[0] == true) {
					$machine_states->machine_running = true;
				}

				$system_steady = $states_object->firstWhere('tag_id', 24);
				if($system_steady && json_decode($system_steady->values)[0] == true) {
					$machine_states->system_steady = true;
				}

				$massflow_hopper_stable = $states_object->firstWhere('tag_id', 25);
				if($massflow_hopper_stable && json_decode($massflow_hopper_stable->values)[0] == true) {
					$machine_states->massflow_hopper_stable = true;
				}

				$rpm = $states_object->firstWhere('tag_id', 27);
				if($rpm && json_decode($rpm->values)[0] == true) {
					$machine_states->rpm = true;
				}
			}
		}

		return response()->json(compact('machine_states'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender
		description: -blender capability, 2 points, 1 min update F17_7_0_system_max (lbs/hr or kgs/hr) REAL
		tag: F17_7_0_system_max
	*/
	public function getBlenderCapabilities(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		if ($request->machineId === MACHINE_BD_BATCH_BLENDER) {
			$tag_id = 17;
			$unit_tag_id = 51;
			$isImperial = $this->isImperial(MACHINE_BD_BATCH_BLENDER, $request->serialNumber);
		} else if ($request->machineId === MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER) {
			$tag_id = 22;
			$unit_tag_id = 56;
			$isImperial = $this->isImperial(MACHINE_BD_BATCH_BLENDER, $request->serialNumber);
		}

		$capabilities_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', $tag_id)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timestamp')
										->get();

		$capabilities = $this->averagedSeries($capabilities_object);

		$items = [$capabilities];

		return response()->json(compact('items', 'isImperial'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender
		description: -current target rate, 1 point, 1 min update F9_43_0_TotalMass (lbs/hr or kgs/hr) REAL 
		tag: F9_43_0_TotalMass
	*/
	public function getTargetRate(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$isImperial = false;

		$unit = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 56)->latest('timedata')->first();

		if($unit)
			$isImperial = json_decode($unit->values)[0];

		$rates_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 18)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timestamp')
										->get();

		$rates = $this->averagedSeries($rates_object);

		$items = [$rates];
		
		return response()->json(compact('items', 'isImperial'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender
		description: -feeder calibration value, 6 points, 1 min update Feeder 1-6: F31_3_0_hop_running_feed_factor[1..6] REAL
		tag: F31_3_0_hop_running_feed_factor
	*/
	public function getFeederCalibrations(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$calibrations_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 21)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timestamp')
										->get();

		$feeders = collect([0, 1, 2, 3, 4, 5]);

		$calibrations = $calibrations_object->map(function($calibration_object) {
			return [($calibration_object->timestamp + $this->timeshift) * 1000, json_decode($calibration_object->values)];
		});

		// $calibrations = $this->averagedSeries($calibrations_object);

		$items = $feeders->map(function ($feeder, $i) use ($calibrations) {
			$item = new stdClass();
			$item->name = 'Feeder ' . ($i + 1);
			$item->data = collect($calibrations)->map(function($calibration, $key) use ($i) {
				return [$calibration[0], round($calibration[1][$i], 2)];
			});

			return $item;
		});

		return response()->json(compact('items'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender
		description: -feeder speed, 6 points, 1 min update Feeder 1-6 motor speed: F9_21_0_Feeder_RPM[1..6] REAL
		tag: F9_21_0_Feeder_RPM
	*/
	public function getFeederSpeeds(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$speeds_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 19)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timestamp')
										->get();

		$speeds = $speeds_object->map(function($speed_object) {
			return [($speed_object->timestamp + $this->timeshift) * 1000, json_decode($speed_object->values)];
		});

		$feeders = collect([0, 1, 2, 3, 4, 5]);

		$items = $feeders->map(function ($feeder, $i) use ($speeds) {
			$item = new stdClass();
			$item->name = 'Feeder ' . ($i + 1);
			$item->data = collect($speeds)->map(function($speed, $key) use ($i) {
				return [$speed[0], round($speed[1][$i], 2)];
			});

			return $item;
		});

		return response()->json(compact('items'));
	}

	/*
		Get Target and actual pump hours oil change in VTC Plus Conveying System configuration
		params: device_id
		return: Actual and target pump hours oil change
	*/
	public function getPumpHoursOil(Request $request) {
		$targets = [];
		$actuals = [];
		
		$targetValues = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 17)
						->latest('timedata')
						->first();
		$actualValues = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 16)
						->latest('timedata')
						->first();

		if($actualValues)
			$actuals = json_decode($actualValues->values);
		if($targetValues)
			$targets = json_decode($actualValues->values);

		$items = [$actuals, $targets];
		return response()->json(compact('items'));
	}

	/*
		description: online life
		params: device_id
		return: online life in %
	*/
	public function getPumpOnlineLife(Request $request) {
		$items = $hours = $targets = $actuals = [];

		$hourValues = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 15)
			->latest('timedata')
			->first();

		$targetValues = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 17)
			->latest('timedata')
			->first();

		$actualValues = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 16)
			->latest('timedata')
			->first();

		if($hourValues) {
			$hours = json_decode($hourValues->values);
		}

		if($targetValues) {
			$targets = json_decode($targetValues->values);
		}

		if($actualValues) {
			$actuals = json_decode($actualValues->values);
		}

		foreach ($hours as $key => $value) {
			if($actuals[$key] == 0)
				$items[$key] = 100;
			else
				$items[$key] = round(100 * ($targets[$key] - $value) / $actuals[$key], 2);
		}

		return response()->json(compact('items'));
	}

	/*
		Get pump hours in VTC Plus Conveying System configuration
		params: device_id
		return: 12 sized array of hours
	*/
	public function getPumpHours(Request $request) {
		$items = [];
		
		$hourValues = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 15)
						->latest('timedata')
						->first();

		if($hourValues)
			$items = json_decode($hourValues->values);

		return response()->json(compact('items'));
	}

	/*
		description: Get pump online states
		configuration: VTC Plus Conveying System
	*/
	public function getPumpOnlines(Request $request) {
		$onlines = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		
		$onlines_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 12)
						->latest('timedata')
						->first();

		if($onlines_object)
			$onlines = json_decode($onlines_object->values);

		return response()->json(compact('onlines'));
	}

	/*
		description: -pump blowback engaged, 12 points, on change (10min-4hr) pump_blowback_on[1..12], INT
		configuration: VTC Plus Conveying System
	*/
	public function getPumpBlowBacks(Request $request) {
		$blowbacks = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		
		$blowback_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 14)
						->latest('timedata')
						->first();

		if($blowback_object)
			$blowbacks = json_decode($blowback_object->values);

		return response()->json(compact('blowbacks'));
	}

	/*
		Get Drying hopper states for NGX Dryer
	*/
	public function getDryingHopperStates(Request $request) {
		$states = new stdClass();

		$states->hopper1 = 0;
		$states->hopper2 = 0;
		$states->hopper3 = 0;

		$hopper1 = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 33)->latest('timedata')->first();

		if($hopper1) {
			$states->hopper1 = json_decode($hopper1->values)[0];
		}

		$hopper2 = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 34)->latest('timedata')->first();

		if($hopper2) {
			$states->hopper2 = json_decode($hopper2->values)[0];
		}

		$hopper3 = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 35)->latest('timedata')->first();

		if($hopper3) {
			$states->hopper3 = json_decode($hopper3->values)[0];
		}

		return response()->json(compact('states'));
	}

	/*
		configuration: Accumeter Ovation Continuous Blender configuration
		description: Get product production rate
		return: Rate Series Array
	*/
	public function getDewPointTemperature(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$tag_id = $request->machineId === 6 ? 18 : 12;

		$temperatures_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', $tag_id)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timedata')
										->get();

		$temperatures = $temperatures_object->map(function($t) {
			return [$t->timestamp * 1000, round((json_decode($t->values)[0] - 32) * 5 / 9, 2)];
		});

		$items = [$temperatures];

		return response()->json(compact('items'));
	}

	public function getRegionAirTemperature(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$items = [];

		$tag_ids = $request->machineId === 6 ? [22, 20, 21, 23] : [16, 14, 15, 17];
		$names = ['Set Point', 'Regen Left', 'Regen Right', 'Regen Exhause'];

		for ($i=0; $i < 4; $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)
											->where('tag_id', $tag_ids[$i])
											->orderBy('timestamp')
											->where('timestamp', '>', $from)
											->where('timestamp', '<', $to)
											->get();

			if($obj) {
				$data = $obj->map(function($t) {
					return [$t->timestamp * 1000, round((json_decode($t->values)[0] - 32) * 5 / 9, 2)];
				});

				$rg_l = new stdClass();
				$rg_l->name = $names[$i];
				$rg_l->data = $data;
				$items[$i] = $rg_l;
			}
		}

		return response()->json(compact('items'));
	}

	/*
		Get Target, actual and outlet hopper temperatures
		params: device_id
	*/
	public function getHopperTemperatures(Request $request) {
		if ($request->machineId === 6) {
			$inlets = [0, 0, 0];
			$outlets = [0, 0, 0];
			$targets = [0, 0, 0];

			$tag_1_ids = [9, 11, 10];
			$tag_2_ids = [12, 14, 13];
			$tag_3_ids = [15, 17,16];

			$tag_1_ids = [9, 12, 15];
			$tag_2_ids = [11, 14, 13];
			$tag_3_ids = [10, 13,16];

			for ($i=0; $i < 3; $i++) { 
				$inlet = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', $tag_1_ids[$i])
						->latest('timedata')
						->first();
				$outlet = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', $tag_2_ids[$i])
						->latest('timedata')
						->first();
				$target = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', $tag_3_ids[$i])
						->latest('timedata')
						->first();

				if($inlet) $inlets[$i] = round((json_decode($inlet->values)[0] - 32) * 5 / 9, 2);
				if($outlet) $outlets[$i] = round((json_decode($outlet->values)[0] - 32) * 5 / 9, 2);
				if($target) $targets[$i] = round((json_decode($target->values)[0] - 32) * 5 / 9, 2);
			}
		} else {
			$inlets = [0];
			$outlets = [0];
			$targets = [0];

			$tag_ids = [9, 11, 10];

			for ($i=0; $i < 1; $i++) {
				$inlet = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', $tag_ids[0])
						->latest('timedata')
						->first();
				$outlet = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', $tag_ids[1])
						->latest('timedata')
						->first();
				$target = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', $tag_ids[2])
						->latest('timedata')
						->first();

				if($inlet) $inlets[$i] = round((json_decode($inlet->values)[0] - 32) * 5 / 9, 2);
				if($outlet) $outlets[$i] = round((json_decode($outlet->values)[0] - 32) * 5 / 9, 2);
				if($target) $targets[$i] = round((json_decode($target->values)[0] - 32) * 5 / 9, 2);
			}
		}

		$items = [$inlets, $outlets, $targets];

		return response()->json([
			'items' => $items,
			'unit' => '℃'
		]);
	}

	/*
		description: get bed states
		tags: 	-left bed in regen, 1 point, on change (1hr-4hr) R16_LB_IN_REGEN bool
				-left bed regen heating, 1 point, on change (1hr-4hr) R02_LB_REG_HEATING bool
				-left bed regen cooling, 1 point, on change (1hr-4hr) R06_LB_REG_COOLING bool
				-right bed in regen, 1 point, on change (1hr-4hr) R17_RB_IN_REGEN bool
				-right bed regen heating, 1 point, on change (1hr-4hr) R03_RB_REG_HEATING bool
				-right bed regen cooling, 1 point, on change (1hr-4hr) R07_RB_REG_COOLING bool
	*/
	public function getNgxDryerBedStates(Request $request) {
		$states = [
			[ "name" => "Left bed in regen", "value" => false ],
			[ "name" => "Left bed regen heating", "value" => false ],
			[ "name" => "Left bed regen cooling", "value" => false ],
			[ "name" => "Right bed in regen", "value" => false ],
			[ "name" => "Right bed regen heating", "value" => false ],
			[ "name" => "Right bed regen cooling", "value" => false ]
		];

		$tag_ids = $request->machineId === 6 ? [26, 27, 28, 29, 30, 31] : [20, 21, 22, 23, 24, 25];

		for ($i=0; $i < 6; $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $tag_ids[$i])->latest('timedata')->first();

			if($obj) {
				$states[$i]["value"] = json_decode($obj->values)[0];
			}
		}

		return response()->json([
			'states' => $states
		]);
	}

	public function getNgxDryerOnlineHours(Request $request) {
		if ($request->machineId === 6) {
			$hours = [
				[ 'name' => 'DH1 Online Hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'DH2 Online Hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'DH3 Online Hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'Dryer Online Hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'Blower Run Hours', 'maint_value' => 0, 'total_value' => 0 ]
			];

			$maint_tags = [40, 42, 44, 50, 52];
			$total_tags = [41, 43, 45, 51, 53];
		} else {
			$hours = [
				[ 'name' => 'Dryer Online Hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'Process Blower Run Hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'Left Regen Heater hours', 'maint_value' => 0, 'total_value' => 0 ],
				[ 'name' => 'Right Regen Heater hours', 'maint_value' => 0, 'total_value' => 0 ]
			];

			$maint_tags = [34, 36, 40, 42];
			$total_tags = [35, 37, 41, 43];
		}

		for ($i=0; $i < count($maint_tags); $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $maint_tags[$i])->latest('timedata')->first();

			if($obj) {
				$hours[$i]['maint_value'] = json_decode($obj->values)[0];
			}

			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $total_tags[$i])->latest('timedata')->first();

			if($obj) {
				$hours[$i]['total_value'] = json_decode($obj->values)[0];
			}
		}

		return response()->json([
			'hours' => $hours
		]);
	}

	public function getNomadHopperStates(Request $request) {
		$states = [
			[ "name" => "Drying Hopper Status", "value" => 0 ],
			[ "name" => "Convey status", "value" => 0 ],
			[ 'name' => 'Machine loader 1 status', 'value' => 0 ],
			[ 'name' => 'Machine loader 2 status', 'value' => 0 ],
			[ 'name' => 'Hopper loader status', 'value' => 0 ],

		];

		$tag_ids = [27, 55, 58, 59, 60];

		for ($i=0; $i < count($tag_ids); $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $tag_ids[$i])->latest('timedata')->first();

			if($obj) {
				$states[$i]["value"] = json_decode($obj->values)[0];
			}
		}

		return response()->json([
			'states' => $states
		]);
	}
	/*
		configuration: NGX Dryer
		description: Reg air temperature
		tag: 
	*/
	// public function getRegAirTemperature($id) {
	// 	$product = Device::where('serial_number', $id)->first();

	// 	if(!$product) {
	// 		return response()->json([
	// 			'message' => 'Device Not Found'
	// 		], 404);
	// 	}

	// 	$items = [0, 0, 0];
	// 	$unit = 0;

	// 	$unit_object = DeviceData::where('device_id', $id)
	// 		->where('machine_id', MACHINE_TRUETEMP_TCU)
	// 		->where('tag_id', 7)
	// 		->latest('timedata')
	// 		->first();
	// 	if($unit_object) {
	// 		$unit = json_decode($unit_object->values)[0];
	// 	}

	// 	$air_object = DeviceData::where('device_id', $id)
	// 		->where('machine_id', MACHINE_TRUETEMP_TCU)
	// 		->where('tag_id', 2)
	// 		->latest('timedata')
	// 		->first();

	// 	if($delivery_object) {
	// 		$items[0] = json_decode($delivery_object->values)[0];
	// 	}

	// 	$actual_object = DeviceData::where('device_id', $id)
	// 		->where('machine_id', MACHINE_TRUETEMP_TCU)
	// 		->where('tag_id', 4)
	// 		->latest('timedata')
	// 		->first();

	// 	if($actual_object) {
	// 		$items[1] = json_decode($actual_object->values)[0];
	// 	}

	// 	$target_object = DeviceData::where('device_id', $id)
	// 									->where('tag_id', 8)
	// 									->latest('timedata')
	// 									->first();

	// 	if($target_object) {
	// 		$items[2] = json_decode($target_object->values)[0];
	// 	}

	// 	if($unit == 1) {
	// 		$items[0] = round(($items[0] - 32) * 5 / 9, 2);
	// 		$items[1] = round(($items[1] - 32) * 5 / 9, 2);
	// 		$items[2] = round(($items[0] - 32) * 5 / 9, 2);
	// 	}

	// 	return response()->json(compact('items'));
	// }

	/*
		Get Machine state for machine 3
	*/
	public function getMachineStates3(Request $request) {
		$machine_states = new stdClass();

		$machine_states->wtp = false;
		$machine_states->system_steady = false;
		$machine_states->halloff = false;
		$machine_states->rpm = false;

		$wtp = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 26)->latest('timedata')->first();

		if($wtp && json_decode($wtp->values)[0] == true) {
			$machine_states->wtp = true;
		}

		$system_steady = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 25)->latest('timedata')->first();

		if($system_steady && json_decode($system_steady->values)[0] == true) {
			$machine_states->system_steady = true;
		}

		$halloff = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 28)->latest('timedata')->first();

		if($halloff && json_decode($halloff->values)[0] == true) {
			$machine_states->halloff = true;
		}

		$rpm = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 27)->latest('timedata')->first();

		if($rpm && json_decode($rpm->values)[0] == true) {
			$machine_states->rpm = true;
		}

		return response()->json(compact('machine_states'));
	}

	/*
		Get Hopper Inventories in machine 3
		F25_0_0_hopper_inv
		tag_id: 23
		return: array
	*/
	public function getInventories3(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$inventories_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 23)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->latest('timedata')
										->get();

		if($inventories_object) {
			$inventories = $inventories_object->map(function($inventory_object) {
				return [($inventory_object->timestamp + $this->timeshift) * 1000, round(json_decode($inventory_object->values)[0], 2)];
			});
		} else {
			$inventories = [];
		}

		$items = [$inventories];

		return response()->json(compact('items'));
	}

	/*
		Get Hauloff lengths in machine 3
		F31_4_0_hauloff_ac
		tag_id: 24
		return: array
	*/
	public function getHauloffLengths(Request $request) {
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$lengths_object = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 24)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->latest('timedata')
										->get();

		if($lengths_object) {
			$lengths = $lengths_object->map(function($length_object) {
				return [($length_object->timestamp + $this->timeshift) * 1000, round(json_decode($length_object->values)[0], 2)];
			});
		} else {
			$lengths = [];
		}

		$items = [$lengths];
		
		return response()->json(compact('items'));
	}

	/*
		configuration: True temp tcu
		description: actual target temperature
		tag: Return Temp, Setpoint 1
	*/
	public function getTcuActTgtTemperature(Request $request) {
		$items = [0, 0, 0];
		$unit = 0;

		$unit_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 7)
			->latest('timedata')
			->first();
		if($unit_object) {
			$unit = json_decode($unit_object->values)[0];
		}

		$delivery_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 2)
			->latest('timedata')
			->first();

		if($delivery_object) {
			$items[0] = json_decode($delivery_object->values)[0];
		}

		$actual_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 4)
			->latest('timedata')
			->first();

		if($actual_object) {
			$items[1] = json_decode($actual_object->values)[0];
		}

		$target_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 8)
			->latest('timedata')
			->first();

		if($target_object) {
			$items[2] = json_decode($target_object->values)[0];
		}

		if($unit == 1) {
			$items[0] = round(($items[0] - 32) * 5 / 9, 2);
			$items[1] = round(($items[1] - 32) * 5 / 9, 2);
			$items[2] = round(($items[0] - 32) * 5 / 9, 2);
		}

		return response()->json(compact('items'));
	}

	/*
		MOST IMPORTANT: Process out temperature Int(xxxx.x) 300005
		tag_id: 85
		return: array
	*/
	public function getProcessOutTemperature(Request $request) { 
		$items = [0, 0];

		$actual_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 80)
			->latest('timedata')
			->first();

		if($actual_object) {
			$items[0] = json_decode($actual_object->values)[0];
		}

		$target_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 77)
			->latest('timedata')
			->first();

		if($target_object) {
			$items[1] = json_decode($target_object->values)[0];
		}

		return response()->json(compact('items'));
	}

	public function getCentralChillerTemperature(Request $request) { 
		$items = [0, 0];

		$tag_id_in = 3;
		$tag_id_out = 4;

		switch ($request->circuitId) {
			case 1:
				$tag_id_in = 3;
				$tag_id_out = 4;
				break;

			case 2:
				$tag_id_in = 19;
				$tag_id_out = 20;
				break;
				
			case 3:
				$tag_id_in = 35;
				$tag_id_out = 36;
				break;
				
			case 4:
				$tag_id_in = 51;
				$tag_id_out = 52;
				break;
				
			case 5:
				$tag_id_in = 67;
				$tag_id_out = 68;
				break;
				
			case 6:
				$tag_id_in = 83;
				$tag_id_out = 84;
				break;
				
			case 7:
				$tag_id_in = 99;
				$tag_id_out = 100;
				break;
				
			case 8:
				$tag_id_in = 115;
				$tag_id_out = 116;
				break;

			case 9:
				$tag_id_in = 131;
				$tag_id_out = 132;
				break;

			case 10:
				$tag_id_in = 147;
				$tag_id_out = 148;
				break;

			default:
				break;
		}

		$chill_in_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', $tag_id_in)
			->latest('timedata')
			->first();

		if($chill_in_object) {
			$items[0] = json_decode($chill_in_object->values)[0];
		}

		$chill_out_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', $tag_id_out)
			->latest('timedata')
			->first();

		if($chill_out_object) {
			$items[1] = json_decode($chill_out_object->values)[0];
		}

		return response()->json(compact('items'));
	}


	public function getT50Runnings(Request $request) {
		$states = [
			[ 'name' => 'Granulator motor', 'value' => 0 ],
			[ 'name' => 'Convey motor', 'value' => 0 ],
			[ 'name' => 'Blow motor', 'value' => 0 ],
			[ 'name' => 'Aux1 motor', 'value' => 0 ],
			[ 'name' => 'Aux2 motor', 'value' => 0 ]
		];

		$tags = [19, 20, 21, 22, 23];

		for ($i=0; $i < 5; $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $tags[$i])->latest('timedata')->first();

			if($obj) {
				$states[$i]['value'] = json_decode($obj->values)[0];
			}
		}

		return response()->json([
			'states' => $states
		]);
	}

	public function getT50Hours(Request $request) {
		$hours = [
			[ 'name' => 'Granulator motor', 'maint_value' => 0, 'total_value' => 0 ],
			[ 'name' => 'Convey motor', 'maint_value' => 0, 'total_value' => 0 ],
			[ 'name' => 'Blow motor', 'maint_value' => 0, 'total_value' => 0 ],
			[ 'name' => 'Aux1 motor', 'maint_value' => 0, 'total_value' => 0 ],
			[ 'name' => 'Aux2 motor', 'maint_value' => 0, 'total_value' => 0 ]
		];

		$maint_tags = [29, 30, 31, 32, 33];
		$total_tags = [35, 36, 37, 38, 39];

		for ($i=0; $i < 5; $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $maint_tags[$i])->latest('timedata')->first();

			if($obj) {
				$hours[$i]['maint_value'] = json_decode($obj->values)[0];
			}
		}

		for ($i=0; $i < 5; $i++) { 
			$obj = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', $total_tags[$i])->latest('timedata')->first();

			if($obj) {
				$hours[$i]['total_value'] = json_decode($obj->values)[0];
			}
		}

		return response()->json([
			'hours' => $hours
		]);
	}

	public function getT50BearingTemp(Request $request) {
		$items = [0, 0];

		$temp1_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 26)
						->latest('timedata')
						->first();

		$temp2_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 27)
						->latest('timedata')
						->first();

		if($temp1_object) {
			$items[0] = json_decode($temp1_object->values)[0];
		}

		if($temp2_object) {
			$items[1] = json_decode($temp2_object->values)[0];
		}

		return response()->json([
			'items' => $items,
			'unit' => 'ºC'
		]);
	}

	public function getT50Amps(Request $request) {
		$items = [0, 0];

		$amp1_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 24)
						->latest('timedata')
						->first();

		$amp2_object = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 49)
						->latest('timedata')
						->first();

		if($amp1_object) {
			$items[0] = round(json_decode($amp1_object->values)[0], 2);
		}

		if($amp2_object) {
			$items[1] = round(json_decode($amp2_object->values)[0], 2);
		}

		return response()->json(compact('items'));
	}

	/*
		Get running hours of weekdays
		return: 7 length array
	*/
	public function getWeeklyRunningHours($id) {
		$ret = [0, 0, 0, 0, 0, 0, 0];

		$running_values = DB::table('device_data')
							->where('machine_id', $id)
							->where('tag_id', 9)
							->get()
							->toArray();

		$count = count($running_values);
		
		if($count <= 0)
			return $ret;

		$current_object = [
			"timestamp" => time(),
			"values" => $running_values[$count - 1]->values,
		];

		array_push($running_values, (object)$current_object);

		$start_timestamp = $running_values[0]->timestamp;
		$end_timestamp = $running_values[$count]->timestamp;

		for ($i = $start_timestamp; $i < $end_timestamp; $i += 3600) { 
			$weekday = date('N', $i); //1, 2, 3, 4, 5, 6, 7
			$ret[$weekday - 1] += 1;
		}
		// foreach ($running_values as $key => $item) {
		// 	$weekday = date('N', $item->timestamp); //1, 2, 3, 4, 5, 6, 7
		// 	$seconds_diff = $item->timestamp - $running_values[$key - 1]->timestamp;
		// 	$hours_diff = round($seconds_diff / 3600, 1);
		// 	$is_previous_running = json_decode($running_values[$key - 1]->values)[0] == 1;
		// 	if ($is_previous_running) {
		// 		$ret[$weekday - 1] += $hours_diff;
		// 	}
		// }
		return response()->json([
			'hours' => $ret
		]);
	}

	/*
		Get total running and stopped hours
		Machine: BD Batch Blender
		params: data entries with running tag
		return: 2 length array - [0] is running hours, [1] is stopped hours
	*/
	private function totalRunningPercentage($data) {
		$ret = [0, 0];

		foreach ($data as $key => $item) {
			if($key) {
				$seconds_diff = $item->timestamp - $data[$key - 1]->timestamp;
				$hours_diff = (int)($seconds_diff / 3600);
				$is_previous_running = json_decode($data[$key - 1]->values)[0] == 1;
				if ($is_previous_running) {
					$ret[0] += $hours_diff;
				} else {
					$ret[1] += $hours_diff;
				}
			}
		}
		if($ret[0] + $ret[1])
			return $ret[0] / ($ret[0] + $ret[1]);
		else
			return 0;
	}

	public function getLocationsTableData(Request $request) {
		$user = $request->user('api');

		$locations = $user->getMyLocations();

		foreach ($locations as $key => $location) {
			$downtime_distribution = $this->getDowntimeDistribution(1106550521);
			$location->utilization = '32%';
			$location->color = 'green';
			$location->value = 75;
			$location->oee = '93.1%';
			$location->performance = '78%';
			$location->rate = 56;
			$location->downtimeDistribution = $downtime_distribution;
		}

		return response()->json(compact('locations'));
	}

	public function getZonesTableData($location_id) {
		$location = Location::findOrFail($location_id);

		$zones = $location->zones;

		foreach ($zones as $key => $zone) {
			$downtime_distribution = $this->getDowntimeDistribution(1106550521);
			$zone->utilization = '32%';
			$zone->color = 'green';
			$zone->value = 75;
			$zone->oee = '93.1%';
			$zone->performance = '78%';
			$zone->rate = 56;
			$zone->downtimeDistribution = $downtime_distribution;
		}

		return response()->json(compact('zones'));
	}

	public function getMachinesTableData($zone_id) {
		$devices = Device::where('zone_id', $zone_id)->get();

		foreach ($devices as $key => $device) {
			$downtime_distribution = $this->getDowntimeDistribution(1106550521);
			$device->utilization = '32%';
			$device->color = 'green';
			$device->value = 75;
			$device->oee = '93.1%';
			$device->performance = '78%';
			$device->rate = 56;
			$device->downtimeDistribution = $downtime_distribution;
		}

		return response()->json(compact('devices'));
	}

	public function getDataToolSeries(Request $request) {
		$series = [];
		$tags = $request->selectedTags;
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		foreach ($tags as $key => $tag) {
			$series_obj = DeviceData::where('machine_id', $request->machineId)
				->where('tag_id', $tag['tag_id'])
				->where('serial_number', $request->serialNumber)
				->where('timestamp', '>', $from)
				->where('timestamp', '<', $to)
				->get();

			if($series_obj) {
				$ss = $series_obj->map(function($object) use ($tag) {
					$divide_by = isset($tag['divided_by']) ? $tag['divided_by'] : 1;
					$offset = $tag['offset'] ? $tag['offset'] : 0;
					$bytes = isset($tag['bytes']) ? $tag['bytes'] : 0;
					if ($bytes) {
						$value = ((json_decode($object->values)[0] >> $tag['offset']) & $tag['bytes']);
					} else {
						$value = json_decode($object->values)[$offset] / $divide_by;
					}
					return [($object->timestamp) * 1000, round($value, 2)];
				});
			} else {
				$ss = [];
			}

			$sery = new stdClass();
			$sery->name = $tag['name'];
			$sery->type = isset($tag['type']) ? $tag['type'] : 'line';
			$sery->data = $ss;

			array_push($series, $sery);
		}

		return response()->json(compact('series'));
	}

	/*
		Get downtime distribution of a machine in seconds
		Defalt start time is a week ago and default end time is now
	*/
	public function getDowntimeDistribution($id, $start = 0, $end = 0) {

		if(!$start) $start = strtotime("-7 days");
		if(!$end) $end = time();

		$ret = [0, 0, 0];	// 0 - Planned
							// 1 - Unplanned
							// 2 - Idle

		$device = Device::where('serial_number', $id)->first();
		
		if(!$device) return $ret;

		$running_values = DB::table('runnings')
							->where('device_id', $id)
							->where('tag_id', 9)
							->where('timestamp', '>', $start)
							->where('timestamp', '<', $end)
							->orderBy('timestamp')
							->get();

		$last_before_start = DB::table('runnings')
							->where('device_id', $id)
							->where('tag_id', 9)
							->where('timestamp', '<', $start)
							->orderBy('timestamp')
							->first();

		if ($last_before_start) {
			$last_before_start_running = json_decode($last_before_start->values)[0];
		} else {
			$last_before_start_running = 1;
		}

		$count = $running_values->count();

		if(!$count && !$last_before_start) {
			return $ret;
		}

		$downtime_plans = DowntimePlan::where('machine_id', $id)->get();

		foreach ($running_values as $KEY => $item) {
			if(!$KEY) {
				if($last_before_start_running == 0) {
					// calculate downtime seconds
					$planned = 0;
					foreach ($downtime_plans as $key => $downtime_plan) {
						$planned += $this->overlapInSeconds(
							$start,
							$item->timestamp,
							strtotime($downtime_plan->dateFrom . ' ' . $downtime_plan->timeFrom),
							strtotime($downtime_plan->dateTo . ' ' . $downtime_plan->timeTo)
						);
					}
					$ret[0] += $planned;
					$ret[1] += ($item->timestamp - $start - $planned);
				}
			} else {
				if(json_decode($running_values[$KEY - 1]->values)[0] == 0) {
					$planned = 0;
					foreach ($downtime_plans as $key => $downtime_plan) {
						$planned += $this->overlapInSeconds(
							$running_values[$KEY - 1]->timestamp,
							$item->timestamp,
							strtotime($downtime_plan->dateFrom . ' ' . $downtime_plan->timeFrom),
							strtotime($downtime_plan->dateTo . ' ' . $downtime_plan->timeTo)
						);
					}
					$ret[0] += $planned;
					$ret[1] += ($item->timestamp - $running_values[$KEY - 1]->timestamp - $planned);
				}
			}
		}

		if($count && json_decode($running_values[$count - 1]->values)[0] == 0) {
			$planned = 0;
			foreach ($downtime_plans as $key => $downtime_plan) {
				$planned += $this->overlapInSeconds(
					$running_values[$count - 1]->timestamp,
					$end,
					strtotime($downtime_plan->dateFrom . ' ' . $downtime_plan->timeFrom),
					strtotime($downtime_plan->dateTo . ' ' . $downtime_plan->timeTo)
				);
			}
			$ret[0] += $planned;
			$ret[1] += ($end - $running_values[$count - 1]->timestamp - $planned);
		}
		
		return $ret;
	}

	/**
	 * What is the overlap, in seconds, of two time periods?
	 *
	 * @param $start1   string
	 * @param $end1     string
	 * @param $start2   string
	 * @param $end2     string
	 * @returns int     Overlap in seconds
	 */
	function overlapInSeconds($start1, $end1, $start2, $end2)
	{
	    // Figure out which is the later start time
	    $lastStart = $start1 >= $start2 ? $start1 : $start2;

	    // Figure out which is the earlier end time
	    $firstEnd = $end1 <= $end2 ? $end1 : $end2;

	    // Subtract the two, and round down
	    $overlap = floor($firstEnd - $lastStart);

	    // If the answer is greater than 0 use it.
	    // If not, there is no overlap.
	    return $overlap > 0 ? $overlap : 0;
	}

	public function getFromTo($data) {
		switch ($data["timeRangeOption"]) {
			case 'last30Min':
				return [
					"from" => strtotime("-30 min"),
					"to" => time()
				];
			case 'lastHour':
				return [
					"from" => strtotime("-1 hour"),
					"to" => time()
				];
			case 'last4Hours':
				return [
					"from" => strtotime("-4 hours"),
					"to" => time()
				];
			case 'last12Hours':
				return [
					"from" => strtotime("-12 hours"),
					"to" => time()
				];
			case 'last24Hours':
				return [
					"from" => strtotime("-1 day"),
					"to" => time()
				];
			case 'last48Hours':
				return [
					"from" => strtotime("-2 days"),
					"to" => time()
				];
			case 'last3Days':
				return [
					"from" => strtotime("-3 days"),
					"to" => time()
				];
			case 'last7Days':
				return [
					"from" => strtotime("-1 week"),
					"to" => time()
				];
			case 'last24Days':
				return [
					"from" => strtotime("-24 days"),
					"to" => time()
				];
			case 'custom':
				return [
					"from" => strtotime($data["dateFrom"] . ' ' . $data["timeFrom"]),
					"to" => strtotime($data["dateTo"] . ' ' . $data["timeTo"])
				];
				break;
			default:
				break;
		}
	}
}
