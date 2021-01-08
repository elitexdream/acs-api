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
use App\DowntimePlan;
use App\Tag;
use DB;
use \stdClass;

class MachineController extends Controller
{
	private $num_chunks = 12;

	/*
		Get general information of product
		They are Name, Serial number, Software build, and version
		return: Object
	*/
	public function getProductOverview($id) {
		$product = Device::where('serial_number', $id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		if($configuration->id == MACHINE_TRUETEMP_TCU) {
			// product version
			if($version_object = DeviceData::where('device_id', $id)
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
			$tag_software_version = Tag::where('tag_name', 'software_version')->where('configuration_id', $configuration->id)->first();

			if(!$tag_software_version) {
				return response()->json('Software version tag not found', 404);
			}

			// product version
			if($version_object = DB::table('software_version')
								->where('device_id', $id)
								->where('tag_id', $tag_software_version->tag_id)
								->latest('timestamp')
								->first()) {
				try {
					$product->version = json_decode($version_object->values)[0] / 10;
				} catch (\Exception $e) {
					$product->version = '';
				}
			}

			// software build
			$tag_software_build = Tag::where('tag_name', 'software_build')->where('configuration_id', $configuration->id)->first();

			if(!$tag_software_build) {
				return response()->json('Software build tag not found', 404);
			}

			if($software_build_object = DB::table('software_builds')
											->where('device_id', $id)
											->where('tag_id', $tag_software_build->tag_id)
											->latest('timestamp')
											->first()) {
				try {
					$product->software_build = json_decode($software_build_object->values)[0];
				} catch (\Exception $e) {
					$product->software_build = '';
				}
			}

			// serial number
			$serial_year = "";
			$serial_month = "";
			$serial_unit = "";

			$tag_serial_year = Tag::where('tag_name', 'serial_number_year')->where('configuration_id', $configuration->id)->first();
			$tag_serial_month = Tag::where('tag_name', 'serial_number_month')->where('configuration_id', $configuration->id)->first();
			$tag_serial_unit = Tag::where('tag_name', 'serial_number_unit')->where('configuration_id', $configuration->id)->first();

			if(!$tag_serial_year || !$tag_serial_month || !$tag_serial_unit) {
				return response()->json('Serial number tag not found', 404);
			}

			$serial_year_object = DB::table('serial_number_year')
										->where('device_id', $id)
										->where('tag_id', $tag_serial_year->tag_id)
										->latest('timestamp')
										->first();
			$serial_month_object = DB::table('serial_number_month')
										->where('device_id', $id)
										->where('tag_id', $tag_serial_month->tag_id)
										->latest('timestamp')
										->first();
			$serial_unit_object = DB::table('serial_number_unit')
										->where('device_id', $id)
										->where('tag_id', $tag_serial_unit->tag_id)
										->latest('timestamp')
										->first();

			if($serial_year_object) {
				try {
					$serial_year = json_decode($serial_year_object->values)[0];
				} catch (\Exception $e) {
					$serial_year = '';
				}
			}
			if($serial_month_object) {
				try {
					$serial_month = chr(json_decode($serial_month_object->values)[0] + 65);
				} catch (\Exception $e) {
					$serial_month = '';
				}
			}
			if($serial_unit_object) {
				try {
					$serial_unit = json_decode($serial_unit_object->values)[0];
				} catch (\Exception $e) {
					$serial_unit = '';
				}
			}
			$product->serial = mb_convert_encoding($serial_year . $serial_month . $serial_unit, 'UTF-8', 'UTF-8');
		}

		return response()->json([
			"overview" => $product
		]);
	}

	/*
		Get inventories
		L30_0_8_HopInv and L30_16_23_FracInv are grouped together
		return: array
	*/
	public function getInventories($id) {
		$product = Device::where('serial_number', $id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$hop_inventory = DeviceData::where('device_id', $id)->where('tag_id', 15)->latest('timestamp')->first();
		$actual_inventory = DeviceData::where('device_id', $id)->where('tag_id', 16)->latest('timestamp')->first();

		$inventories = array();

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
		
		return response()->json(compact('inventories'));
	}

	/*
		actual and target weight in BD_Batch_Blender
	*/
	public function getProductWeight($id) {
		$weight_objects = DeviceData::where('device_id', $id)
						->whereIn('tag_id', [13, 14])
						->whereJsonLength('values', 8)
						->latest('timestamp')
						->get()
						->unique('tag_id');

		$targets = [];
		$actuals = [];
		
		$target_object = $weight_objects->firstWhere('tag_id', 13);
		$actual_object = $weight_objects->firstWhere('tag_id', 14);

		$targets = json_decode($target_object->values);
		foreach ($targets as $target) {
			$target = $target / 1000;
		}

		$actuals = json_decode($actual_object->values);
		foreach ($actuals as $actual) {
			$actual = $actual / 1000;
		}

		return response()->json(compact('targets', 'actuals'));
	}

	/*
		Get last recipe values in BD Blender configuration
	*/
	public function getProductRecipe($id) {
		$product = Device::where('serial_number', $id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$last_recipe = DeviceData::where('machine_id', $configuration->id)->where('tag_id', 10)->orderBy('timestamp', 'desc')->first();

		if( $last_recipe) {
			$recipe_values = json_decode($last_recipe->values);
		} else {
			$recipe_values = [];
		}

		return response()->json(compact('recipe_values'));
	}

	/*
		Get product utilization series
		return: Utilization Series Array
	*/
	public function getProductUtilization(Request $request) {
		$product = Device::where('serial_number', $request->id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$tag_utilization = Tag::where('tag_name', 'capacity_utilization')->where('configuration_id', $configuration->id)->first();

		if(!$tag_utilization) {
			return response()->json('Capacity utilization tag not found', 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$utilizations_object = DB::table('utilizations')
								->where('device_id', $request->id)
								->where('tag_id', $tag_utilization->tag_id)
								->where('timestamp', '>', $from)
								->where('timestamp', '<', $to)
								->orderBy('timestamp')
								->get();

		$utilizations = $utilizations_object->map(function($utilization_object) {
			return [$utilization_object->timestamp * 1000, json_decode($utilization_object->values)[0]];
		});

		return response()->json(compact('utilizations'));
	}

	/*
		Get energy consumption series
		return: Energy consumption series array
	*/
	public function getEnergyConsumption(Request $request) {
		$product = Device::where('serial_number', $request->id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$tag_energy_consumption = Tag::where('tag_name', 'energy_consumption')->where('configuration_id', $configuration->id)->first();

		if(!$tag_energy_consumption) {
			return response()->json('Energy consumption tag not found', 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$energy_consumptions_object = DB::table('energy_consumptions')
										->where('device_id', $request->id)
										->where('tag_id', $tag_energy_consumption->tag_id)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->orderBy('timestamp')
										->get();

		$energy_consumption = $energy_consumptions_object->map(function($energy_consumption_object) {
			return [$energy_consumption_object->timestamp * 1000, json_decode($energy_consumption_object->values)[0]];
		});

		return response()->json(compact('energy_consumption'));
	}

	/*
		Get Target recipe and actual recipe in Accumeter Ovation Continuous Blender configuration
		params: device_id
		return: Actual and target recipes
	*/
	public function getTgtActualRecipes($id) {
		$targets = [0, 0, 0, 0, 0, 0];
		$actuals = [0, 0, 0, 0, 0, 0];
		
		$targetValues = DeviceData::where('device_id', $id)
						->where('tag_id', 11)
						->latest('timestamp')
						->first();
		
		if($targetValues) {
			$targets = json_decode($targetValues->values);

			$actualValue1 = DeviceData::where('device_id', $id)
						->where('tag_id', 12)
						->orderBy('timestamp', 'DESC')
						->first();
			if($actualValue1)
				$actuals[0] = round(json_decode($actualValue1->values)[0], 2);

			$actualValue2 = DeviceData::where('device_id', $id)
						->where('tag_id', 13)
						->orderBy('timestamp', 'DESC')
						->first();
			if($actualValue2)
				$actuals[1] = round(json_decode($actualValue2->values)[0], 2);

			$actualValue3 = DeviceData::where('device_id', $id)
						->where('tag_id', 14)
						->orderBy('timestamp', 'DESC')
						->first();
			if($actualValue3)
				$actuals[2] = round(json_decode($actualValue3->values)[0], 2);

			$actualValue4 = DeviceData::where('device_id', $id)
						->where('tag_id', 15)
						->orderBy('timestamp', 'DESC')
						->first();
			if($actualValue4)
				$actuals[3] = round(json_decode($actualValue4->values)[0], 2);

			$actualValue5 = DeviceData::where('device_id', $id)
						->where('tag_id', 16)
						->orderBy('timestamp', 'DESC')
						->first();
			if($actualValue5)
				$actuals[4] = round(json_decode($actualValue5->values)[0], 2);

			$actualValue6 = DeviceData::where('device_id', $id)
						->where('tag_id', 17)
						->orderBy('timestamp', 'DESC')
						->first();
			if($actualValue6)
				$actuals[5] = round(json_decode($actualValue6->values)[0], 2);
		}

		return response()->json(compact('targets', 'actuals'));
	}

	/*
		Get Target and actual pump hours oil change in VTC Plus Conveying System configuration
		params: device_id
		return: Actual and target pump hours oil change
	*/
	public function getPumpHoursOil($id) {
		$targets = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		$actuals = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		
		$targetValues = DeviceData::where('device_id', $id)
						->where('tag_id', 17)
						->latest('timestamp')
						->first();
		$actualValues = DeviceData::where('device_id', $id)
						->where('tag_id', 16)
						->latest('timestamp')
						->first();

		if($actualValues)
			$actuals = json_decode($actualValues->values);
		if($targetValues)
			$targets = json_decode($actualValues->values);

		return response()->json(compact('targets', 'actuals'));
	}

	/*
		Get pump hours in VTC Plus Conveying System configuration
		params: device_id
		return: 12 sized array of hours
	*/
	public function getPumpHours($id) {
		$hours = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		
		$hourValues = DeviceData::where('device_id', $id)
						->where('tag_id', 15)
						->latest('timestamp')
						->first();

		if($hourValues)
			$hours = json_decode($hourValues->values);

		return response()->json(compact('hours'));
	}

	/*
		Get Machine state, system steady, mass flow hopper and RPM
	*/
	public function getProductStates($id) {
		$product = Device::where('serial_number', $id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$machine_states = new stdClass();

		if($configuration->id == MACHINE_TRUETEMP_TCU) {
			$machine_states->pump_status = 0;
			$machine_states->heater_status = 0;
			$machine_states->vent_status = 0;
			
			if($pump_status_object = DeviceData::where('device_id', $id)
						->where('tag_id', 40)
						->latest('timestamp')
						->first()) {
				try {
					$machine_states->pump_status = json_decode($pump_status_object->values)[0];
				} catch(Exception $e) {
					$machine_states->pump_status = 0;
				}
			}

			if($heater_status_object = DeviceData::where('device_id', $id)
						->where('tag_id', 41)
						->latest('timestamp')
						->first()) {
				try {
					$machine_states->heater_status = json_decode($heater_status_object->values)[0];
				} catch(Exception $e) {
					$machine_states->heater_status = 0;
				}
			}

			if($vent_status_object = DeviceData::where('device_id', $id)
						->where('tag_id', 42)
						->latest('timestamp')
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

			$machine_running = DeviceData::where('device_id', $id)->where('tag_id', 10)->latest('timestamp')->first();

			if($machine_running && json_decode($machine_running->values)[0] == true) {
				$machine_states->machine_running = true;
			}

			$system_steady = DeviceData::where('device_id', $id)->where('tag_id', 24)->latest('timestamp')->first();

			if($system_steady && json_decode($system_steady->values)[0] == true) {
				$machine_states->system_steady = true;
			}

			$massflow_hopper_stable = DeviceData::where('device_id', $id)->where('tag_id', 25)->latest('timestamp')->first();

			if($massflow_hopper_stable && json_decode($massflow_hopper_stable->values)[0] == true) {
				$machine_states->massflow_hopper_stable = true;
			}

			$rpm = DeviceData::where('device_id', $id)->where('tag_id', 27)->latest('timestamp')->first();

			if($rpm && json_decode($rpm->values)[0] == true) {
				$machine_states->rpm = true;
			}
		}

		return response()->json(compact('machine_states'));
	}

	/*
		Get Drying hopper states for NGX Dryer
	*/
	public function getDryingHopperStates($id) {
		$states = new stdClass();

		$states->hopper1 = 0;
		$states->hopper2 = 0;
		$states->hopper3 = 0;

		$hopper1 = DeviceData::where('device_id', $id)->where('tag_id', 33)->latest('timestamp')->first();

		if($hopper1) {
			$states->hopper1 = json_decode($hopper1->values)[0];
		}

		$hopper2 = DeviceData::where('device_id', $id)->where('tag_id', 34)->latest('timestamp')->first();

		if($hopper2) {
			$states->hopper2 = json_decode($hopper2->values)[0];
		}

		$hopper3 = DeviceData::where('device_id', $id)->where('tag_id', 35)->latest('timestamp')->first();

		if($hopper3) {
			$states->hopper3 = json_decode($hopper3->values)[0];
		}

		return response()->json(compact('states'));
	}

	/*
		Get Target, actual and outlet hopper temperatures
		params: device_id
	*/
	public function getHopperTemperatures($id) {
		$inlets = [0, 0, 0];
		$outlets = [0, 0, 0];
		$targets = [0, 0, 0];
		
		$inletHopper1 = DeviceData::where('device_id', $id)
						->where('tag_id', 9)
						->latest('timestamp')
						->first();
		$inletHopper2 = DeviceData::where('device_id', $id)
						->where('tag_id', 12)
						->latest('timestamp')
						->first();
		$inletHopper3 = DeviceData::where('device_id', $id)
						->where('tag_id', 15)
						->latest('timestamp')
						->first();

		$outletHopper1 = DeviceData::where('device_id', $id)
						->where('tag_id', 11)
						->latest('timestamp')
						->first();
		$outletHopper2 = DeviceData::where('device_id', $id)
						->where('tag_id', 14)
						->latest('timestamp')
						->first();
		$outletHopper3 = DeviceData::where('device_id', $id)
						->where('tag_id', 17)
						->latest('timestamp')
						->first();

		$targetHopper1 = DeviceData::where('device_id', $id)
						->where('tag_id', 10)
						->latest('timestamp')
						->first();
		$targetHopper2 = DeviceData::where('device_id', $id)
						->where('tag_id', 13)
						->latest('timestamp')
						->first();
		$targetHopper3 = DeviceData::where('device_id', $id)
						->where('tag_id', 16)
						->latest('timestamp')
						->first();

		if($inletHopper1) $inlets[0] = json_decode($inletHopper1->values)[0];
		if($inletHopper2) $inlets[1] = json_decode($inletHopper2->values)[0];
		if($inletHopper3) $inlets[2] = json_decode($inletHopper3->values)[0];

		if($outletHopper1) $outlets[0] = json_decode($outletHopper1->values)[0];
		if($outletHopper2) $outlets[1] = json_decode($outletHopper2->values)[0];
		if($outletHopper3) $outlets[2] = json_decode($outletHopper3->values)[0];

		if($targetHopper1) $targets[0] = json_decode($targetHopper1->values)[0];
		if($targetHopper2) $targets[1] = json_decode($targetHopper2->values)[0];
		if($targetHopper3) $targets[2] = json_decode($targetHopper3->values)[0];

		return response()->json(compact('inlets', 'targets', 'outlets'));
	}

	/*
		Get Machine state for machine 3
	*/
	public function getMachineStates3($id) {
		$product = Device::where('serial_number', $id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$machine_states = new stdClass();

		$machine_states->wtp = false;
		$machine_states->system_steady = false;
		$machine_states->halloff = false;
		$machine_states->rpm = false;

		$wtp = DeviceData::where('device_id', $id)->where('tag_id', 26)->latest('timestamp')->first();

		if($wtp && json_decode($wtp->values)[0] == true) {
			$machine_states->wtp = true;
		}

		$system_steady = DeviceData::where('device_id', $id)->where('tag_id', 25)->latest('timestamp')->first();

		if($system_steady && json_decode($system_steady->values)[0] == true) {
			$machine_states->system_steady = true;
		}

		$halloff = DeviceData::where('device_id', $id)->where('tag_id', 28)->latest('timestamp')->first();

		if($halloff && json_decode($halloff->values)[0] == true) {
			$machine_states->halloff = true;
		}

		$rpm = DeviceData::where('device_id', $id)->where('tag_id', 27)->latest('timestamp')->first();

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
		$product = Device::where('serial_number', $request->id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$inventories_object = DeviceData::where('device_id', $request->id)
										->where('tag_id', 23)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->latest('timestamp')
										->get();

		if($inventories_object) {
			$inventories = $inventories_object->map(function($inventory_object) {
				return [$inventory_object->timestamp * 1000, json_decode($inventory_object->values)[0]];
			});
		} else {
			$inventories = [];
		}

		return response()->json(compact('inventories'));
	}

	/*
		Get Hauloff lengths in machine 3
		F31_4_0_hauloff_ac
		tag_id: 24
		return: array
	*/
	public function getHauloffLengths(Request $request) {
		$product = Device::where('serial_number', $request->id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$lengths_object = DeviceData::where('device_id', $request->id)
										->where('tag_id', 24)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->latest('timestamp')
										->get();

		if($lengths_object) {
			$lengths = $lengths_object->map(function($length_object) {
				return [$length_object->timestamp * 1000, round(json_decode($length_object->values)[0], 2)];
			});
		} else {
			$lengths = [];
		}

		return response()->json(compact('lengths'));
	}
	
	/*
		Get Feeder stables
	*/
	public function getFeederStables($id) {
		$product = Device::where('serial_number', $id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$feeders_object = DeviceData::where('device_id', $id)->where('tag_id', 26)->latest('timestamp')->first();

		if($feeders_object) {
			$feeders = json_decode($feeders_object->values);
		} else {
			$feeders = [];
		}

		return response()->json(compact('feeders'));
	}

	/*
		Get product production rate
		return: Rate Series Array
	*/
	public function getProductProcessRate(Request $request) {
		$product = Device::where('serial_number', $request->id)->first();

		if(!$product) {
			return response()->json([
				'message' => 'Device Not Found'
			], 404);
		}

		$configuration = $product->configuration;

		if(!$configuration) {
			return response()->json([
				'message' => 'Device Not Configured'
			], 404);
		}

		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		$process_rates_object = DeviceData::where('device_id', $request->id)
										->where('tag_id', 23)
										->where('timestamp', '>', $from)
										->where('timestamp', '<', $to)
										->latest('timestamp')
										->get();

		$process_rate = $process_rates_object->map(function($process_rate_object) {
			return [$process_rate_object->timestamp * 1000, json_decode($process_rate_object->values)[0]];
		});

		return response()->json(compact('process_rate'));
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

	private function parseValid($raw_array, $i) {
		try {
			$width = $raw_array->count() / $this->num_chunks + 1;
			$chunks = array_chunk(json_decode($raw_array), $width);
			return array_map(function($chunk) use ($i, $width) {
				$sum = 0; $count = 0;
				foreach ($chunk as $key => $item) {
					$sum += json_decode($item)[$i];
					$count ++;
				}
				return (int)($sum / $count);
				// return (int)($sum / $count);
			}, $chunks);
		} catch (\Exception $e) {
			return 0;
		}
	}

	private function parseValid1($raw_array) {
		try {
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
		} catch (\Exception $e) {
			return 0;
		}
	}

	public function parseValidWithTime($raw_array, $i, $from, $to) {
		$width = $raw_array->count() / 12 + 1;
		$chunks = array_chunk(json_decode($raw_array), $width);
		return array_map(function($chunk, $index) use ($i, $width, $from, $to) {
			$sum = 0; $count = 0;
			foreach ($chunk as $key => $item) {
				$sum += json_decode($item)[$i];
				$count ++;
			}
			return [($from + $index * ($to - $from) / 12) * 1000, (int)($sum / $count)];
		}, $chunks, array_keys($chunks));
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
