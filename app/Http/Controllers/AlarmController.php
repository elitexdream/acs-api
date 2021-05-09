<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceData;
use App\AlarmType;
use App\Alarm;
use App\Device;
use App\Machine;
use App\Tag;
use App\Company;
use App\TeltonikaConfiguration;
use App\AlarmStatus;
use DB;
use \stdClass;

class AlarmController extends Controller
{
	public function getAlarmsForCustomerDevices(Request $request) {
		$user = $request->user('api');

		if(!$user) {
			return response()->json('Unauthorized', 401);
		}

		$devices = $user->company->devices;

		foreach ($devices as $key => $device) {
			$alarm_types = AlarmType::where('machine_id', $device->machine_id)->get();
			$alarm_tag_ids = [];

			foreach ($alarm_types as $key => $alarm_type) {
				$alarm_tag_ids[] = $alarm_type->tag_id;
			}
			
			$device->alarms_count = DeviceData::where('device_id', $device->serial_number)->whereIn('tag_id', $alarm_tag_ids)->count();
		}

		return response()->json(compact('devices'));
	}

	public function getAlarmsOverview(Request $request) {
		$machine_types = Machine::select('id', 'name')->get();

		foreach ($machine_types as $key => &$machine_type) {
			if ($machine_type->id != 1)
				continue;
			$alarm_types = AlarmType::select('name', 'bytes', 'offset', 'tag_id')->where('machine_id', $machine_type->id)->orderBy('id')->get();
			$tag_ids = $alarm_types->unique('tag_id')->pluck('tag_id');

			$alarms_object = Alarm::where('machine_id', $machine_type->id)
									->whereIn('tag_id', $tag_ids)
									->get();

			foreach ($alarms_object as $alarm_object) {
				$value32 = json_decode($alarm_object->values);

				$alarm_types_for_tag = $alarm_types->filter(function ($alarm_type, $key) use ($alarm_object) {
				    return $alarm_type->tag_id == $alarm_object->tag_id;
				});

				foreach ($alarm_types_for_tag as &$alarm_type) {
					try {
						$active = false;
						if($alarm_type->bytes == 0 && $alarm_type->offset == 0)
							$active = $value32[0];
						else if($alarm_type->bytes == 0 && $alarm_type->offset != 0) {
							$active = $value32[$alarm_type->offset];
						} else if($alarm_type->bytes != 0) {
							$active = ($value32[0] >> $alarm_type->offset) & $alarm_type->bytes;
						}

						if($active) {
							$alarm_type->machine_name = $machine_type->name;
							$alarm_type->count++;
						}
					} catch (\Exception $e) {

					}
				}
			}

			$machine_type->alarm_types = $alarm_types->toArray();
		}

		$alarm_arrays = [];

		foreach ($machine_types as $key => $machine_type) {
			if ($machine_type->alarm_types) {
				$alarm_arrays = array_merge($alarm_arrays, $machine_type->alarm_types);
			}
		}

		$max1 = collect($alarm_arrays)->max('count');
		$max2 = collect($alarm_arrays)->max('count');
		$max3 = collect($alarm_arrays)->max('count');

		return response()->json([
			'top_alarms' => [ $max1, $max2, $max3 ],
			'total' => collect($alarm_arrays)->sum('count')
		]);
	}

	public function getAlarmsForCompany(Request $request) {
		$company = Company::findOrFail($request->companyId);
		$devices = $company->devices->where('machine_id', $request->machineId)->pluck('serial_number');

		$alarm_types = AlarmType::select('name', 'bytes', 'offset', 'tag_id')->where('machine_id', $request->machineId)->orderBy('id')->get();
		foreach ($alarm_types as $key => &$type) {
			$type->counts = 0;
		}

		$tag_ids = $alarm_types->unique('tag_id')->pluck('tag_id');

		foreach ($devices as $key => $device) {
			$teltonika = TeltonikaConfiguration::where('teltonika_id', $device)->first();

			if ($teltonika)
				$serial_number = $teltonika->plc_serial_number;
			else
				continue;

			$alarms_object = Alarm::where('serial_number', $serial_number)
									->whereIn('tag_id', $tag_ids)
									->orderBy('timestamp', 'DESC')
									->get();

			foreach ($alarms_object as $alarm_object) {
				$value32 = json_decode($alarm_object->values);

				$alarm_types_for_tag = $alarm_types->filter(function ($alarm_type, $key) use ($alarm_object) {
				    return $alarm_type->tag_id == $alarm_object->tag_id;
				});

				foreach ($alarm_types_for_tag as &$alarm_type) {
					$active = false;
					if($alarm_type->bytes == 0 && $alarm_type->offset == 0)
						$active = $value32[0];
					else if($alarm_type->bytes == 0 && $alarm_type->offset != 0) {
						$active = $value32[$alarm_type->offset];
					} else if($alarm_type->bytes != 0) {
						$active = ($value32[0] >> $alarm_type->offset) & $alarm_type->bytes;
					}

					if($active)
						$alarm_type->counts++;
				}
			}
		}

		return response()->json(compact('alarm_types'));
	}

    public function getProductAlarms(Request $request) {
		$alarm_types = AlarmType::where('machine_id', $request->machineId)->orderBy('id')->get();
		$tag_ids = $alarm_types->unique('tag_id')->pluck('tag_id');

		$alarms_object = Alarm::where('serial_number', $request->serialNumber)
								->whereIn('tag_id', $tag_ids)
								->orderBy('timestamp', 'DESC')
								->get()
								->unique('tag_id');

		$alarms = [];

		foreach ($alarms_object as $alarm_object) {
			$value32 = json_decode($alarm_object->values);

			$alarm_types_for_tag = $alarm_types->filter(function ($alarm_type, $key) use ($alarm_object) {
			    return $alarm_type->tag_id == $alarm_object->tag_id;
			});

			foreach ($alarm_types_for_tag as $alarm_type) {

				$alarm = new stdClass();

				$alarm->id = $alarm_object->id;
				$alarm->tag_id = $alarm_object->tag_id;
				$alarm->timestamp = $alarm_object->timestamp * 1000;
				if($alarm_type->bytes == 0 && $alarm_type->offset == 0)
					$alarm->active = $value32[0];
				else if($alarm_type->bytes == 0 && $alarm_type->offset != 0) {
					$offset = isset($tag['offset']) ? $tag['offset'] : 0;
					$alarm->active = !!$value32[$offset] == true;
				} else if($alarm_type->bytes != 0) {
					$alarm->active = ($value32[0] >> $alarm_type->offset) & $alarm_type->bytes;
				}

				$alarm->type_id = $alarm_type->id;

				array_push($alarms, $alarm);
			}
		}

		return response()->json(compact('alarms', 'alarm_types'));
	}

	public function getProductAlarmHistory(Request $request) {
		$alarms_object = AlarmStatus::where('machine_id', $request->machineId)
									->where('timestamp', '>', $request->from)
									->where('timestamp', '<', $request->to)
									->orderBy('tag_id')
									->orderBy('offset')
									->orderBy('timestamp')
									->get();

		$alarm_types = AlarmType::where('machine_id', $request->machineId)->orderBy('id')->get();
		$alarms = [];

		foreach ($alarm_types as $alarm_type) {
			$alarms_for_tag = $alarms_object->filter(function ($alarm_object, $key) use ($alarm_type) {
				return $alarm_object->tag_id == $alarm_type->tag_id && $alarm_object->offset == $alarm_type->offset;
			});

			if (count($alarms_for_tag) > 0) {
				$alarm_info = new stdClass();
				$temp = 0;
				$completed_array = false;
				$i = 0;

				foreach ($alarms_for_tag as $key => $alarm_for_tag) {
					$alarm_info->name = $alarm_type->name;
					if ($alarm_for_tag->is_activate && $temp == 0) {
						$temp = 1;
						$alarm_info->activate = $alarm_for_tag->timestamp;
					} else if (!$alarm_for_tag->is_activate && $temp == 1) {
						$temp = 0;
						$alarm_info->resolve = $alarm_for_tag->timestamp;
						$completed_array = true;
					}

					if ($completed_array) {
						array_push($alarms, $alarm_info);
						$completed_array = false;
						$alarm_info = new stdClass();
					}

					if ($i == count($alarms_for_tag) - 1 && $temp == 1) {
						$alarm_info->resolve = -1;
						array_push($alarms, $alarm_info);
						$alarm_info = new stdClass();
					}

					$i++;
				}
			}
		}

		return response()->json(compact('alarms'));
	}

	public function getAlarmsReports(Request $request) {
		$user = $request->user('api');
		$machine_ids = $user->company->devices->pluck('machine_id');
		$alarm_types = AlarmType::whereIn('machine_id', $machine_ids)->orderBy('id')->get();
		$tag_ids = $alarm_types->unique('tag_id')->pluck('tag_id');

		$alarms_object = Alarm::whereIn('tag_id', $tag_ids)
								->orderBy('timestamp', 'DESC')
								->get()
								->unique('tag_id');

		$alarms = [];

		foreach ($alarms_object as $alarm_object) {
			$value32 = json_decode($alarm_object->values);

			$alarm_types_for_tag = $alarm_types->filter(function ($alarm_type, $key) use ($alarm_object) {
			    return $alarm_type->tag_id == $alarm_object->tag_id;
			});

			foreach ($alarm_types_for_tag as $alarm_type) {

				$alarm = new stdClass();

				$alarm->id = $alarm_object->id;
				$alarm->tag_id = $alarm_object->tag_id;
				$alarm->timestamp = $alarm_object->timestamp * 1000;
				if($alarm_type->bytes == 0 && $alarm_type->offset == 0)
					$alarm->active = $value32[0];
				else if($alarm_type->bytes == 0 && $alarm_type->offset != 0) {
					$offset = isset($tag['offset']) ? $tag['offset'] : 0;
					$alarm->active = !!$value32[$offset] == true;
				} else if($alarm_type->bytes != 0) {
					$alarm->active = ($value32[0] >> $alarm_type->offset) & $alarm_type->bytes;
				}

				$alarm->type_id = $alarm_type->id;

				$configuration = TeltonikaConfiguration::where('plc_serial_number', $alarm_type->serial_number)->first();

				$machine_info = Device::where('serial_number', $configuration->teltonika_id)->first();

				$alarm->machine_info = $machine_info;

				array_push($alarms, $alarm);
			}
		}

		foreach ($alarm_types as $alarm_type) {
			$machine = Machine::select('name')->where('id', $alarm_type->machine_id)->get()->first();

			$alarm_type['machine_name'] = $machine->name;
		}

		return response()->json(compact('alarms', 'alarm_types'));
	}

	public function getAlarmTypesByMachineId($id) {
		$alarm_types = AlarmType::select('name')
							->where('machine_id', $id)
							->get();

		return $alarm_types;
	}
	
	public function getMachineIdByMachineName($machine_name) {		
		$machineInfo = Device::select('machine_id', 'serial_number')->where('customer_assigned_name', $machine_name)->get()->first();

		return $machineInfo;
	}

	public function getAssignedMachinesByCompanyId($company_id) {
		// $query = 'SELECT machines.id, machines.name FROM devices INNER JOIN machines ON devices.machine_id = machines.id WHERE devices.company_id = ' . $company_id;
		// $assigned_machines = DB::select(DB::raw($query));
		
		$assigned_machines = Device::select('machines.id', 'machines.name')
									->join('machines', 'devices.machine_id', '=', 'machines.id')
									->where('devices.company_id', $company_id)
									->get();
		return $assigned_machines;
	}

	public function getBasicDeviceData($company_id, $machine_id = 0, $dates = NULL) {
		if ($machine_id) {
			// $query = 'SELECT alarm_types.name, alarm_types.machine_id, alarm_types.tag_id, alarm_types.machine_id FROM alarm_types WHERE alarm_types.machine_id = '. $machine_id;
			$alarm_types = AlarmType::select('name', 'machine_id', 'tag_id', 'offset')
									->where('machine_id', $machine_id)
									->get();			
		} else {
			$assigned_devices = Device::select('machine_id')->where('company_id', $company_id)->get();
			$machine_ids = [];
			foreach($assigned_devices as $assigned_device) {
				$machine_ids[] = $assigned_device['machine_id'];
			}
	
			// $query = 'SELECT alarm_types.name, alarm_types.machine_id, alarm_types.tag_id, alarm_types.machine_id FROM alarm_types WHERE alarm_types.machine_id IN (' 
			// 	. implode("," , $machine_ids) . 
			// 	')';
			$alarm_types = AlarmType::select('name', 'machine_id', 'tag_id', 'offset')
				->whereIn('machine_id', $machine_ids)
				->get();
		}
		// $alarm_types = DB::select(DB::raw($query));

		$tag_ids = [];
		foreach($alarm_types as $alarm_type) {
			$tag_ids = array_merge($tag_ids, [json_decode($alarm_type->tag_id)]);
		}

		// $query = 'SELECT device_data.tag_id, device_data.timestamp, device_data.values, device_data.machine_id from device_data WHERE device_data.tag_id IN (' 
		// 			. implode("," , $tag_ids) . ') AND device_data.customer_id = ' . $company_id;

		if ($machine_id) {
			// $query .= ' AND device_data.machine_id = ' . $machine_id;
			$device_data = Alarm::select('tag_id', 'timestamp', 'values', 'machine_id')
									->whereIn('tag_id', $tag_ids)
									->where('machine_id', $machine_id)
									->orderBy('timestamp')
									->get();
		}

		if ($dates) {
			// $query .= ' AND device_data.timestamp BETWEEN ' . strtotime($dates[0]) . ' AND ' . strtotime($dates[1]);
			$device_data = Alarm::select('tag_id', 'timestamp', 'values', 'machine_id')
									->whereIn('tag_id', $tag_ids)
									->whereBetween('timestamp', [strtotime($dates[0]), strtotime($dates[1])])
									->orderBy('timestamp')
									->get();
		}

		// $device_data = DB::select(DB::raw($query));

		foreach($device_data as $item) {
			$sum = 0;
			$value = json_decode($item->values);
			foreach($value as $num) {
				$sum += $num;
			}
			$item->values = $sum;
			$item->times = 1;
			$alarm_type_name = AlarmType::select('name')->where('machine_id', $item->machine_id)->where('tag_id', 'LIKE', '%' . $item->tag_id . '%')->get();
			$item->alarm_type_name = $alarm_type_name->first()->name;
		}

		return $device_data;
	}

	public function getBasicDeviceDataWithSerial($machine_id, $serial_number) {
		$alarm_types = AlarmType::select('name', 'machine_id', 'tag_id', 'offset')
									->where('machine_id', $machine_id)
									->get();

		$tag_ids = [];
		foreach($alarm_types as $alarm_type) {
			$tag_ids = array_merge($tag_ids, [json_decode($alarm_type->tag_id)]);
		}

		$device_data = DeviceData::select('tag_id', 'timestamp', 'values', 'machine_id')
									->whereIn('tag_id', $tag_ids)
									->where('machine_id', $machine_id)
									->where('serial_number', $serial_number)
									->orderBy('timestamp')
									->get();


		foreach($device_data as $item) {
			$sum = 0;
			$value = json_decode($item->values);
			foreach($value as $num) {
				$sum += $num;
			}
			$item->values = $sum;
			$item->times = 1;
			$alarm_type_name = AlarmType::select('name')->where('machine_id', $item->machine_id)->where('tag_id', 'LIKE', '%' . $item->tag_id . '%')->get();
			$item->alarm_type_name = $alarm_type_name->first()->name;
		}

		return $device_data;
	}

	public function gatherAlarmsByType($company_id, $machine_id = 0, $dates = NULL) {
		$device_data = $this->getBasicDeviceData($company_id, $machine_id, $dates);

		foreach($device_data as $key => $item) {
			for($i = $key + 1; $i < count($device_data); $i ++) {
				if ($item->machine_id == $device_data[$i]->machine_id && $item->tag_id == $device_data[$i]->tag_id) {
					$item->times ++;
					$item->values += $device_data[$i]->values;
				}
			}
		}

		usort($device_data, function ($a, $b) {
			return $b->values - $a->values;
		});	

		return $device_data;
	}

	/// --- Main Functions ---- ///
	public function getSeverityByCompanyId(Request $request) {

		$device_data = $this->gatherAlarmsByType($request->company_id, 0, $request->dates);
		$severity = [];
		if ($device_data) {
			$severity[] = $device_data[0];
			$i = 1;
			foreach($device_data as $item) {
				if ($severity[$i - 1]->tag_id != $item->tag_id || $severity[$i - 1]->machine_id != $item->machine_id ) {
					$severity[] = $item;
					$i ++;
				}
				if ($i == 3)
					break;
			}
		}		
		return response()->json(compact('severity'));
	}

	public function getAlarmsPerTypeByMachine(Request $request) {

		$machine_id = $this->getMachineIdByMachineName($request->machine_name);
		$device_data = $this->gatherAlarmsByType($request->company_id, $machine_id, $request->dates);
		$alarm_type_names = $this->getAlarmTypesByMachineId($machine_id);

		$alarms = [];
		foreach($alarm_type_names as $alarm_type) {
			$flag = 0;
			foreach($device_data as $item) {
				if ($alarm_type->name == $item->alarm_type_name) {
					$alarms[] = [
						'name'		=>	$item->alarm_type_name,
						'values'	=> 	$item->values
					];
					break;
				}
			}
		}
		return response()->json(compact('alarms'));
	}

	public function getAlarmsDistributionByMachine(Request $request) {
		$user = $request->user('api');
		$machine_id = $this->getMachineIdByMachineName($request->machine_name)->machine_id;
		$serial_number = $this->getMachineIdByMachineName($request->machine_name)->serial_number;
		if ($user->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
			$device_data = $this->getBasicDeviceData($request->company_id, $machine_id, $request->dates);
		} else {
			$device_data = $this->getBasicDeviceDataWithSerial($machine_id, $serial_number);
		}
		$alarm_type_names = $this->getAlarmTypesByMachineId($machine_id);

		$data = [];
		if (!count($alarm_type_names))
			$data = [0, 0];
		foreach($alarm_type_names as $item) {
			$results[] = (object) array(
				'name' => $item->name,
				'data' => $data
			);
		}
		foreach($device_data as $row) {
			foreach($results as $item) {
				$val = 0;
				if ($item->name == $row->alarm_type_name) {
					if ($row->values !== 0) {
						$val = 1;
					}
				}
				$item->data[] = [$row->timestamp * 1000, $val];
			}
		}
		foreach($results as $item) {
			$val = 0;
			for ($i = 0; $i < count($item->data); $i++) {
				if ($item->data[$i][1] === 1 ) {
					$val += 1;
				}
				$item->data[$i][1] = $val;
			}
		}
		return response()->json(compact('results'));
	}

	public function getAlarmsAmountPerMachineByCompanyId(Request $request) {

		$device_data = $this->gatherAlarmsByType($request->company_id, 0, $request->dates);
		$results = $this->getAssignedMachinesByCompanyId($request->company_id);

		foreach($results as $machine) {
			$value = 0;
			$tag_ids = [];
			foreach($device_data as $item) {
				if (!in_array($item->tag_id, $tag_ids)) {
					if ($item->machine_id == $machine->id) {
						$value += $item->values;
						$tag_ids[] = $item->tag_id;
					}
				}
			}
			$machine->amount = $value;
		}
		return response()->json(compact('results'));
	}
}
