<?php

namespace App\Http\Controllers;

use App\Running;
use App\SerialNumberMonth;
use App\SerialNumberUnit;
use App\SerialNumberYear;
use App\SoftwareBuild;
use App\SoftwareVersion;
use App\Timezone;
use Illuminate\Http\Request;

use App\Company;
use App\AlarmType;
use App\DeviceData;
use App\Machine;
use App\Location;
use App\Zone;
use App\SavedMachine;
use App\User;
use App\Role;
use App\UserRole;
use App\Device;
use App\TeltonikaConfiguration;
use App\DowntimePlan;
use App\Tag;
use App\InventoryMaterial;
use App\SystemInventory;
use App\MachineTag;
use App\Report;
use App\Alarm;
use App\AvailabilityPlanTime;
use App\HopperClearedTime;
use App\Exports\MachinesReportSheetExport;
use App\Mail\RequestService;
use GuzzleHttp\Client;

use DB;
use Mail;
use File;
use \stdClass;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class MachineController extends Controller
{
	private $num_chunks = 12;
	private $timeshift = 0;
	private $bearer_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJqdGkiOiI2MjQxODgwMjFiMWIwY2UwNTA5ZDE3OWUzY2IxMDgxOGM2YmUzMjlhNjY3NTMwOGU0ZGI4NTEwODU4OThlZGUzNjY0NDQwODA1MDkwZWJjNSIsImlzcyI6Imh0dHBzOlwvXC9ybXMudGVsdG9uaWthLW5ldHdvcmtzLmNvbVwvYWNjb3VudCIsImlhdCI6MTYwNTY2NzMyNywibmJmIjoxNjA1NjY3MzI3LCJleHAiOjE2MzcyMDMzMjcsInN1YiI6IjI3OTcwIiwiY2xpZW50X2lkIjoiOTEyM2VhNjYtMmYxZC00MzljLWIxYzItMzExYWMwMTBhYWFkIiwiZmlyc3RfcGFydHkiOmZhbHNlfQ.I0kEBbsYDzIsBr3KFY9utxhSuKLM0zRgrPUBcUUNrIU3V58tce3LUgfV6r8yip5_pOe3ybVQdEoyIXNuehPUDIa8ZxJYadGw15cs9PLDyvM00ipAggnCgi0QinxUcb_5QjaMqfemhTlil9Zquly-P9tGy8GuT-QKAxMMCwGgou_LA3JH-5c7hoImbINMMyWQaHIrK3IiSVXyb0k_tP2tczy7TIjM5NFdzTMZXlVYEwTRZJ7U-_Vyb0ZnyyTJ_Y6_6CNp79vtQ8kVD_Xs_MVCQ0vQbO9qPRAxNu8noq7ZVo1eRdc1Q411puyzm3MeVSg1bWqqG4QboGiMYTyYclwhqA";

    public function __construct()
    {
    	$user = auth('api')->user();
		$timezone = Timezone::where('id', $user->profile->timezone)->first();
		if($timezone) {
			date_default_timezone_set($timezone->name);

			$this->timeshift = date('Z');
		}
    }

    public function averagedSeries($collection, $series_count = 200, $devide_by = 1) {
    	$total = $collection->count();
		$chunks = $collection->chunk($total / $series_count + 1);

		$ret = $chunks->map(function($chunk) use ($devide_by) {
			$timestamp = ($chunk->first()->timestamp) * 1000;
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
            $running = Running::where('serial_number', $serialNumber)
                ->where('tag_id', $tag->tag_id)
                ->latest('timestamp')
                ->first();

            if ($running) {
				if ($machineId == MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER || $machineId == MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER) {
					return !json_decode($running->values)[0];
				} else {
					return json_decode($running->values)[0];
				}
            }
        }

        return false;
    }

	public function getPlcStatus($deviceId) {
		$getLink = 'https://rms.teltonika-networks.com/api/devices/' . $deviceId;

		$client = new Client();

		try {
			$response = $client->get(
				$getLink,
				[
					'headers' => [
						'Authorization' => "Bearer " . $this->bearer_token,
						'Accept' => "application/json"
					]
				]
			);

			return json_decode($response->getBody())->data;
		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
	}

	public function getMachines(Request $request) {
        $user = $request->user('api');

        $location = $request->location;
        $zone = $request->zone;

        if($user->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
            $devices = Device::where('location_id', $location)->where('zone_id', $zone)->get();
        } else {
            $devices = $user->company->devices()->where('location_id', $location)->where('zone_id', $zone)->get();
        }

        return response()->json(compact('devices'));
    }

    public function generateMachinesReport(Request $request) {
        try {

            if (!Excel::store(new MachinesReportSheetExport($request), 'report.xlsx')) {
                return response()->json([
                    'message' => 'Error generating report',
                    'count' => 0
                ]);
            }

            File::move(storage_path('app/report.xlsx'), public_path(Report::REPORT_PATH . $request->reportTitle . '.xlsx'));

            Report::where('filename', $request->reportTitle)->updateOrCreate([
                'filename' => $request->reportTitle
            ], [
                'from' => $request->timeRange['dateFrom'] . ' ' . $request->timeRange['timeFrom'],
                'to' => $request->timeRange['dateTo'] . ' ' . $request->timeRange['timeTo']
            ]);

            return response()->json([
                'message' => 'Successfully generated',
                'filename' => $request->reportTitle,
                'count' => count($request->machineTags)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'count' => 0
            ]);
        }
    }

	public function getMachinesReport() {
		$reports = Report::all();

		return response()->json(compact('reports'));
	}

	public function deleteMachinesReport($id) {
		$report = Report::where('id', $id)->first();

		if ($report) {
			if (File::exists(public_path(Report::REPORT_PATH . $report->filename . '.xlsx'))) {
				File::delete(public_path(Report::REPORT_PATH . $report->filename . '.xlsx'));
				$status = true;
				$message = 'Report deleted successfully.';
			} else {
				$status = false;
				$message = 'File does not exists';
			}

			$report->delete();
		}

		return response()->json([
			'status' => $status,
			'message' => $message,
			'reports' => Report::all()
		]);

	}
	/*
		Get general information of product
		They are Name, Serial number, Software build, and version
		return: Object
	*/
	public function getProductOverview(Request $request) {
		$user = $request->user('api');
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
				if($version_object = DeviceData::where('serial_number', $request->serialNumber)
									->where('tag_id', $tag_software_version)
									->latest('timestamp')
									->first()) {
					try {
						$product->version = json_decode($version_object->values)[0] / 10;
					} catch (\Exception $e) {
						$product->version = '';
					}
				}
			} else if($request->machineId == MACHINE_GP_PORTABLE_CHILLER) {
				$software_version_x = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 99)
										->latest('timestamp')
										->first();

				$software_version_y = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 100)
										->latest('timestamp')
										->first();

				$software_version_z = DeviceData::where('serial_number', $request->serialNumber)
										->where('tag_id', 101)
										->latest('timestamp')
										->first();

				$version_x = $software_version_x ? json_decode($software_version_x->values)[0] : '0';
				$version_y = $software_version_y ? sprintf('%02d', json_decode($software_version_y->values)[0]) : '00';
				$version_z = $software_version_z ? sprintf('%03d', json_decode($software_version_z->values)[0]) : '000';

				$product->version = mb_convert_encoding($version_x . "." . $version_y . "." . $version_z, 'UTF-8', 'UTF-8');
			} else {
				$tag_software_version = Tag::where('tag_name', 'software_version')->where('configuration_id', $request->machineId)->first();
				if(!$tag_software_version) {
					return response()->json('Software version tag not found', 404);
				}

				// product version
				if($version_object = SoftwareVersion::where('serial_number', $request->serialNumber)
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
				if($software_build_object = SoftwareBuild::where('serial_number', $request->serialNumber)
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
					$serial_year_object = SerialNumberYear::where('serial_number', $request->serialNumber)
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
					$serial_month_object = SerialNumberMonth::where('serial_number', $request->serialNumber)
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
					$serial_unit_object = SerialNumberUnit::where('serial_number', $request->serialNumber)
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
			$plcLinkStatus = $configuration->plc_status;
		}

		if ($request->machineId == MACHINE_VTC_PLUS_CONVEYING_SYSTEM) {
			$plus_model = '';

			$plus_model_object = DeviceData::where('serial_number', $request->serialNumber)->where('tag_id', 9)->latest('timedata')->first();

			if ($plus_model_object) {
				$plus_model = json_decode($plus_model_object->values)[0] + 1;
				$product->teltonikaDevice->name = $product->teltonikaDevice->name . ' (VTC Plus' . $plus_model . ')';
			}
		}

		$product->running = $this->isPlcRunning($request->machineId, $request->serialNumber);

		$saved_machine = SavedMachine::where('user_id', $user->id)
									->where('device_id', $product->teltonikaDevice->id)->first();

		$plcStatus = $this->getPlcStatus($product->teltonikaDevice->device_id);

		if (!isset($plcStatus->status)) {
			$product->status = 'routerNotConnected';
		} else {
			if($plcStatus->status != 1) {
				$product->status = 'routerNotConnected';
			} else if (!$plcLinkStatus) {
				$product->status = 'plcNotConnected';
			} else if ($product->running) {
				$product->status = 'running';
			} else {
				$product->status = 'shutOff';
			}
		}

		if (!$saved_machine) {
			$product->isSavedMachine = false;
		} else {
			$product->isSavedMachine = true;
		}

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

		$last_cleared = HopperClearedTime::where('serial_number', $request->serialNumber)->first();

		return response()->json([
			'data' => [
				'inventories' => $inventories,
				'inventory_material' => $inventory_material,
				'unit' => $isImperial ? 'lbs' : 'kgs',
				'last_cleared_time' => $last_cleared ? $last_cleared->timestamp * 1000 : 0
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
				if ($i == 0) {
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
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		if ($request->machineId == 11) {
			$utilizations_object = DB::table('utilizations')
									->where('serial_number', $request->serialNumber)
									->where('tag_id', 28)
									->where('timestamp', '>', $from)
									->where('timestamp', '<', $to)
									->orderBy('timestamp')
									->get();

			if (!$utilizations_object) {
				$utilizations_object = DB::table('utilizations')
									->where('serial_number', $request->serialNumber)
									->where('tag_id', 29)
									->where('timestamp', '>', $from)
									->where('timestamp', '<', $to)
									->orderBy('timestamp')
									->get();

				$utilizations = $this->averagedSeries($utilizations_object, 200, 10);
			} else {
				$utilizations = $this->averagedSeries($utilizations_object, 200, 10);
			}
		} else {
			$tag_utilization = Tag::where('tag_name', 'capacity_utilization')->where('configuration_id', $request->machineId)->first();

			if(!$tag_utilization) {
				return response()->json('Capacity utilization tag not found', 404);
			}

			$utilizations_object = DB::table('utilizations')
									->where('serial_number', $request->serialNumber)
									->where('tag_id', $tag_utilization->tag_id)
									->where('timestamp', '>', $from)
									->where('timestamp', '<', $to)
									->orderBy('timestamp')
									->get();

			$utilizations = $this->averagedSeries($utilizations_object, 200, 10);

		}
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

	public function requestService(Request $request) {
		$user = $request->user('api');
		$company = Company::where('id', $request->user['companyId'])->first();
		$request['company_name'] = $company->name;
		$request['phone'] = $user->profile->phone;
		Mail::to($request->email)->send(new RequestService($request));
		return response()->json([
			'message' => 'Sent service request successfully'
		]);
	}

	public function saveMachine(Request $request) {
		$user = $request->user('api');

		$saved_machine = SavedMachine::where('user_id', $user->id)
									->where('device_id', $request->deviceId)->first();

		if($saved_machine) {
			$saved_machine->delete();
			return response()->json([
				'status' => false,
				'message' => 'Removed from Favorite'
			]);
		} else {
			SavedMachine::create([
				'user_id' => $user->id,
				'device_id' => $request->deviceId
			]);
			return response()->json([
				'status' => true,
				'message' => 'Added to Favorites'
			]);
		}
	}

	public function getSavedStatus(Request $request) {
		$user = $request->user('api');

		$status = SavedMachine::where('user_id', $user->id)
								->where('device_id', $request->deviceId)->first();

		if (!$status) {
			return response()->json([
				'status' => false
			]);
		} else {
			return response()->json([
				'status' => true
			]);
		}
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
			$targets = json_decode($targetValues->values);

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

		$hopperCount = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 72)
						->latest('timedata')
						->first();

		$numberOfHoppers = $hopperCount ? json_decode($hopperCount->values)[0] : 1;

		return response()->json(compact('states', 'numberOfHoppers'));
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
		$names = ['Set Point', 'Regen Left', 'Regen Right', 'Regen Exhaust'];

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

			$hopperCount = DeviceData::where('serial_number', $request->serialNumber)
						->where('tag_id', 72)
						->latest('timedata')
						->first();
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

				$hopperCount = false;
			}
		}

		$items = [$inlets, $targets, $outlets];

		return response()->json([
			'items' => $items,
			'unit' => '℃',
			'hopperCount' => $hopperCount ? json_decode($hopperCount->values)[0] : 1
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
			$items[0] = round($items[0] * 9 / 5 + 32, 2);
			$items[1] = round($items[1] * 9 / 5 + 32, 2);
			$items[2] = round($items[2] * 9 / 5 + 32, 2);
		}

		return response()->json([
			'items' => $items,
			'unit' => $unit == 0 ? 'ºC' : 'ºF'
		]);
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
			$items[1] = round(json_decode($actual_object->values)[0] / 10, 1);
		}

		$target_object = DeviceData::where('serial_number', $request->serialNumber)
			->where('tag_id', 77)
			->latest('timedata')
			->first();

		if($target_object) {
			$items[0] = round(json_decode($target_object->values)[0] / 10, 1);
		}

		return response()->json([
			'items' => $items,
			'unit' => 'ºC'
		]);
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

		$running_values = DeviceData::where('machine_id', $id)
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

		if ($request->companyId == 0) {
			$locations = $user->getMyLocations();
		} else {
			$customer_admin_role = Role::findOrFail(ROLE_CUSTOMER_ADMIN);
			$customer_admin = $customer_admin_role->users->where('company_id', $request->companyId)->first();
			$locations = $customer_admin->getMyLocations();
		}

		foreach ($locations as $key => $location) {
			$downtime_by_reason = $this->getDowntimeDistribution($location->id);
			$downtime_availability = $this->getDowntimeAvailability($location->id);
			$alarms_count = $this->getAlarmsForLocation($location->id);
			$location->downtimeByReason = $downtime_by_reason;
			$location->downtimeAvailability = $downtime_availability;
			$location->alarmsCount = $alarms_count;
		}

		return response()->json(compact('locations'));
	}

	public function getZonesTableData($location_id) {
		$location = Location::findOrFail($location_id);

		$zones = $location->zones;

		foreach ($zones as $key => $zone) {
			$downtime_by_reason = $this->getDowntimeDistribution($location->id, $zone->id);
			$downtime_availability = $this->getDowntimeAvailability($location->id, $zone->id);
			$alarms_count = $this->getAlarmsForLocation($location->id, $zone->id);
			$zone->downtimeByReason = $downtime_by_reason;
			$zone->downtimeAvailability = $downtime_availability;
			$zone->alarmsCount = $alarms_count;
		}

		return response()->json(compact('zones'));
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
				->orderBy('timestamp')
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
					return [($object->timestamp) * 1000, round($value, 3)];
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

	public function getBlenderWeights(Request $request) {
		$series = [];
		$hoppers = $request->selectedHoppers;
		$from = $this->getFromTo($request->timeRange)["from"];
		$to = $this->getFromTo($request->timeRange)["to"];

		foreach ($hoppers as $key => $hopper) {
			$diff_sum = 0;
			$target_obj = DeviceData::where('machine_id', $request->machineId)
				->where('tag_id', 13)
				->where('serial_number', $request->serialNumber)
				->where('timestamp', '>', $from)
				->where('timestamp', '<', $to)
				->orderBy('timestamp')
				->get();

			if($target_obj) {
				$ss = $target_obj->map(function($object) use ($hopper) {
					$value = json_decode($object->values)[$hopper['id']] / 1000;
					return [($object->timestamp) * 1000, round($value, 3)];
				});
				$target_sd = $target_obj->map(function($object) use ($hopper) {
					$value = json_decode($object->values)[$hopper['id']] / 1000;
					return round($value, 3);
				});
			} else {
				$ss = [];
			}

			$sery = new stdClass();
			$sery->name = $hopper['name'] . ' Target';
			$sery->type = 'line';
			$sery->data = $ss;

			array_push($series, $sery);

			$actual_obj = DeviceData::where('machine_id', $request->machineId)
				->where('tag_id', 14)
				->where('serial_number', $request->serialNumber)
				->where('timestamp', '>', $from)
				->where('timestamp', '<', $to)
				->orderBy('timestamp')
				->get();

			if($actual_obj) {
				$sss = $actual_obj->map(function($object) use ($hopper) {
					$value = json_decode($object->values)[$hopper['id']] / 1000;
					return [($object->timestamp) * 1000, round($value, 3)];
				});
				$actual_sd = $actual_obj->map(function($object) use ($hopper) {
					$value = json_decode($object->values)[$hopper['id']] / 1000;
					return round($value, 3);
				});
			} else {
				$sss = [];
			}

			for ($i=0; $i < count($actual_sd); $i++) {
				if ($target_sd[$i]) {
					$diff = $actual_sd[$i] - $target_sd[$i];
					$diff_sum = $diff_sum + abs($diff);
				} else {
					$diff_sum = $diff_sum + abs($actual_sd[$i]);
				}
			}

			if (count($actual_sd) != 0) {
				$average_error = round($diff_sum / count($actual_sd), 3);
			} else {
				$average_error = 0;
			}

			$seryt = new stdClass();
			$seryt->name = $hopper['name'] . ' Actual';
			$seryt->type = 'line';
			$seryt->data = $sss;
			if (count($actual_sd) != 0) {
				$seryt->sd = round($this->Stand_Deviation($actual_sd->toArray()), 3);
				$seryt->min = min($actual_sd->toArray());
				$seryt->max = max($actual_sd->toArray());
			} else {
				$seryt->sd = 0;
				$seryt->min = 0;
				$seryt->max = 0;
			}
			$seryt->average_error = $average_error;

			array_push($series, $seryt);
		}

		return response()->json(compact('series'));
	}

	/*
		Get downtime distribution of a machine in seconds
		Defalt start time is a day ago and default end time is now
	*/
	public function getDowntimeDistribution($location, $zone = 0, $start = 0, $end = 0) {

		if(!$start) $start = strtotime("-1 day");
		if(!$end) $end = time();

		$devices = Device::where('location_id', $location)->pluck('serial_number')->toArray();

		$ids = implode(", ", $devices);

		if ($ids == "") {
			$additional_query = "and downtimes.device_id in (0)";
		} else {
			$additional_query = "and downtimes.device_id in ($ids)";
		}

		$query  = "select
				overall_subquery.reason_name as name,
				ROUND(sum(overall_subquery.hours_sum)::numeric, 3) as data
			from (
				select
					detailed_subquery.reason_name as reason_name,
					detailed_subquery.output_date_int as output_date_int,
					coalesce(sum(detailed_subquery.corrected_downtime_end_int - detailed_subquery.corrected_downtime_start_int)/(60*60), 0) as hours_sum
				from (
					with
					input_params as (
						select
							$start as start_datetime,
							$end as end_datetime
					),
					datetime_config as (
						select 60*60*24 as day_duration
					),
					output_dates as (
						select generate_series(
							date_trunc('day', to_timestamp(input_params.start_datetime)),
							case when 
								extract(hour from to_timestamp(input_params.end_datetime)) = 0 
								and extract(minute from to_timestamp(input_params.end_datetime)) = 0 
								and extract(second from to_timestamp(input_params.end_datetime)) = 0
							then
								date_trunc('day', to_timestamp(input_params.end_datetime - datetime_config.day_duration))
							else
								to_timestamp(input_params.end_datetime)
							end,
							interval '1 day'
						) as generated_date
						from input_params, datetime_config
					),
					output_dates_int as (
						select
							extract(epoch from generated_date) as date
						from output_dates
					)
					
					select
						output_dates_int.date as output_date_int,
						downtime_reasons.name as reason_name,
					
						case when downtimes.start_time is not null then
							greatest(input_params.start_datetime, output_dates_int.date, downtimes.start_time)
						else
							null
						end as corrected_downtime_start_int,
					
						case when downtimes.start_time is not null then
							least(input_params.end_datetime, output_dates_int.date + datetime_config.day_duration, downtimes.end_time)
						else
							null
						end as corrected_downtime_end_int
					
					from input_params
					left join output_dates_int on 0 = 0
					left join downtime_reasons on 0 = 0
					left join datetime_config on 0 = 0
					left join downtimes on downtimes.reason_id = downtime_reasons.id
						and downtimes.start_time <= least(output_dates_int.date + datetime_config.day_duration, input_params.end_datetime)
						and downtimes.end_time >= greatest(output_dates_int.date, input_params.start_datetime)
						$additional_query
					order by output_dates_int.date, downtime_reasons.name, downtimes.start_time
					
				) as detailed_subquery
				group by detailed_subquery.reason_name, detailed_subquery.output_date_int
			) as overall_subquery
			group by overall_subquery.reason_name";

		$series = DB::select($query);

		foreach ($series as $data) {
			$data->data = json_decode($data->data);
		};

		return $series;
	}

	/**
	 * Get downtime availability in the time period
	 * 
	 * @param $location integer
	 * @param $zone 	integer
	 * @param $start	timestamp
	 * @param $end		timestamp
	 * @return			object
	 */
	public function getDowntimeAvailability($location, $zone = 0, $start = 0, $end = 0) {
		if(!$start) $start = strtotime("-1 day");
		if(!$end) $end = time();

		$dates = [];

		if (!$zone) {
			$devices = Device::where('location_id', $location)->pluck('serial_number')->toArray();
		} else {
			$devices = Device::where('location_id', $location)->where('zone_id', $zone)->pluck('serial_number')->toArray();
		}

		$ids = implode(", ", $devices);

		if ($ids == "") {
			$additional_query = "and downtimes.device_id in (0)";
		} else {
			$additional_query = "and downtimes.device_id in ($ids)";
		}

		$query = "select
                aggregated_subquery.reason_name as name,
                json_agg(ROUND(aggregated_subquery.hours_sum::numeric, 3) order by aggregated_subquery.output_date_int) as data
            from (
                select
                    detailed_subquery.reason_name as reason_name,
                    detailed_subquery.output_date_int as output_date_int,
                    coalesce(sum(detailed_subquery.corrected_downtime_end_int - detailed_subquery.corrected_downtime_start_int)/(60*60), 0) as hours_sum
                from (
                    with
                    input_params as (
                        select
                            $start as start_datetime,
                            $end as end_datetime
                    ),
                    datetime_config as (
                        select 60*60*24 as day_duration
                    ),
                    output_dates as (
                        select generate_series(
                            date_trunc('day', to_timestamp(input_params.start_datetime)),
                            case when 
                                extract(hour from to_timestamp(input_params.end_datetime)) = 0 
                                and extract(minute from to_timestamp(input_params.end_datetime)) = 0 
                                and extract(second from to_timestamp(input_params.end_datetime)) = 0
                            then
                                date_trunc('day', to_timestamp(input_params.end_datetime - datetime_config.day_duration))
                            else
                                to_timestamp(input_params.end_datetime)
                            end,
                            interval '1 day'
                        ) as generated_date
                        from input_params, datetime_config
                    ),
                    output_dates_int as (
                        select
                            extract(epoch from generated_date) as date
                        from output_dates
                    )
                    
                    select
                        output_dates_int.date as output_date_int,
                        downtime_reasons.name as reason_name,
                    
                        case when downtimes.start_time is not null then
                            greatest(input_params.start_datetime, output_dates_int.date, downtimes.start_time)
                        else
                            null
                        end as corrected_downtime_start_int,
                    
                        case when downtimes.start_time is not null then
                            least(input_params.end_datetime, output_dates_int.date + datetime_config.day_duration, downtimes.end_time)
                        else
                            null
                        end as corrected_downtime_end_int
                    
                    from input_params
                    left join output_dates_int on 0 = 0
                    left join downtime_reasons on 0 = 0
                    left join datetime_config on 0 = 0
                    left join downtimes on downtimes.reason_id = downtime_reasons.id
                        and downtimes.start_time <= least(output_dates_int.date + datetime_config.day_duration, input_params.end_datetime)
                        and downtimes.end_time >= greatest(output_dates_int.date, input_params.start_datetime)
                        $additional_query
                    order by output_dates_int.date, downtime_reasons.name, downtimes.start_time
                    
                ) as detailed_subquery
                group by detailed_subquery.reason_name, detailed_subquery.output_date_int
                
            ) as aggregated_subquery
            group by aggregated_subquery.reason_name";

		$date_generate_query = "select generate_series(
			date_trunc('day', to_timestamp($start)),
			case when
				extract(hour from to_timestamp($end)) = 0
				and extract(minute from to_timestamp($end)) = 0
				and extract(second from to_timestamp($end)) = 0
			then
				date_trunc('day', to_timestamp($end - 60*60*24))
			else
				to_timestamp($end)
			end,
			interval '1 day'
			)::date as generated_date";

		$series = DB::select($query);
		$generated_dates = DB::select($date_generate_query);

		foreach ($series as $data) {
            $data->data = json_decode($data->data);
        };

		$availability_target = new stdClass();
		$availability_target->name = 'Target Availability';
		$availability_target->data = [];

		$availability_actual = new stdClass();
		$availability_actual->name = 'Actual Availability';
		$availability_actual->data = [];

		$availability_series = [];

		foreach ($generated_dates as $key => $date) {
			array_push($dates, $date->generated_date);
			$target = AvailabilityPlanTime::where('timestamp', '<=', strtotime($date->generated_date))->orderBy('timestamp', 'DESC')->first();
			if ($target) {
				array_push($availability_target->data, round($target->hours / 24, 3));
				$actual = 0;
				foreach ($series as $data) {
					$actual += $data->data[$key];
				}
				array_push($availability_actual->data, round(($target->hours - $actual) / $target->hours, 3));
			} else {
				array_push($availability_target->data, round(16 / 24, 3));
				$actual = 0;
				foreach ($series as $data) {
					$actual += $data->data[$key];
				}
				array_push($availability_actual->data, round((16 - $actual) / 16, 3));
			}
		};

		array_push($availability_series, $availability_target);
        array_push($availability_series, $availability_actual);

		return $availability_series;
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
					"from" => strtotime('-7 days', time()),
					"to" => time()
				];
			case 'last24Days':
				return [
					"from" => strtotime('-24 days', time()),
					"to" => time()
				];
			case 'custom':
				return [
					"from" => strtotime($data["dates"]['0']),
					"to" => strtotime($data["dates"]['1'])
				];
				break;
			default:
				break;
		}
	}

	// function to calculate the standard deviation of array elements
    public function Stand_Deviation($arr)
    {
        $num_of_elements = count($arr);

        $variance = 0.0;

		// calculating mean using array_sum() method
        $average = array_sum($arr)/$num_of_elements;

        foreach($arr as $i)
        {
            // sum of squares of differences between
			// all numbers and means.
            $variance += pow(($i - $average), 2);
        }

        return (float)sqrt($variance/$num_of_elements);
    }

	/**
	 * Get number of alarms
	 * 
	 * @param $location		integer
	 * @param $zone 		integer
	 */
	public function getAlarmsForLocation($location, $zone = 0) {
		if (!$zone) {
			$additional_query = "devices.location_id = $location";
		} else {
			$additional_query = "devices.location_id = $location and devices.zone_id = $zone";
		}

		$query = "select
				sum(enriched_alarm_details.sensor_last_value)
			from (
				select
					alarm_details.tag_id,
					alarm_details.machine_id,
					alarm_details.device_id,
					alarm_details.latest_values_array,
					alarm_details.bytes,
					alarm_details.offset,
					case
					when alarm_details.bytes = 1 then
						-- when bytes = 1 and values array length > 1 there is a wrong value in values data; do not mean it
						case
						when json_array_length(alarm_details.latest_values_array) > 1 then 0
						else
							case
							when (trim(lower(alarm_details.latest_values_array ->> 0)) = 'true' or alarm_details.latest_values_array ->> 0 = '1') then 1
							when (trim(lower(alarm_details.latest_values_array ->> 0)) = 'false' or alarm_details.latest_values_array ->> 0 = '0') then 0
							-- when it is bigint value then cast it into binary, make right shift for offset value and then takes last digit from binary representaion of new value
							else ((latest_values_array ->> 0)::bigint::bit(32) >> alarm_details.offset)::bigint::bit(1)::integer
							end
						end
					when alarm_details.bytes = 0 then
						case
						when alarm_details.offset >= json_array_length(alarm_details.latest_values_array) then 0
						else
							case
							when (trim(lower(alarm_details.latest_values_array ->> alarm_details.offset)) = 'true' or alarm_details.latest_values_array ->> alarm_details.offset = '1') then 1
							when (trim(lower(alarm_details.latest_values_array ->> alarm_details.offset)) = 'false' or alarm_details.latest_values_array ->> alarm_details.offset = '0') then 0
							else 1
							end
						end
					else 0
					end as sensor_last_value
				from (
					with
					-- get devices set with filter by location_id
					devices as (
						select
							devices.serial_number as serial_number,
							devices.machine_id as machine_id
						from devices
						where
							$additional_query
					),
					aggregated_alarms as (
						select
							tag_id,
							devices.machine_id,
							device_id,
							(
								select
									latest_alarm.values
								from alarms as latest_alarm
								where
									latest_alarm.device_id = alarms.device_id
									and latest_alarm.tag_id = alarms.tag_id
								order by latest_alarm.timestamp desc
								limit 1
							) as latest_values_array
						from devices
						join alarms on alarms.device_id = devices.serial_number::bigint
							-- TODO: stub for incorrect data trouble bypassing; delete after improvements
							and alarms.machine_id != 11
						group by tag_id, devices.machine_id, device_id
					)
					select
						aggregated_alarms.tag_id,
						aggregated_alarms.machine_id,
						aggregated_alarms.device_id,
						aggregated_alarms.latest_values_array,
						alarm_types.bytes,
						alarm_types.offset
					from aggregated_alarms
					left join alarm_types on alarm_types.tag_id = aggregated_alarms.tag_id and alarm_types.machine_id = aggregated_alarms.machine_id
				) as alarm_details
			) as enriched_alarm_details";

		$alarm_count = DB::select($query);
		
		return $alarm_count ? $alarm_count : 0;
	}
}
