<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DeviceData;
use App\AlarmType;
use App\Alarm;
use App\Device;
use App\Machine;
use App\Tag;
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
				$alarm_tag_ids = array_merge(json_decode($alarm_type->tag_id));
			}
			
			$device->alarms_count = DeviceData::where('device_id', $device->serial_number)->whereIn('tag_id', $alarm_tag_ids)->count();
		}

		return response()->json(compact('devices'));
	}

    public function getProductAlarms(Request $request, $id) {
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
		
		$alarm_types = AlarmType::where('machine_id', $configuration->id)->orderBy('id')->get();
		$tag_ids = $alarm_types->unique('tag_id')->pluck('tag_id');
		
		$alarms_object = Alarm::where('device_id', $id)
								->whereIn('tag_id', $tag_ids)
								->latest('timestamp')
								->get()
								->unique('tag_id');

		$alarms = [];

		$s = 0;
		foreach ($alarms_object as $alarm_object) {
			$value32 = json_decode($alarm_object->values);

			$alarm_types_for_tag = $alarm_types->filter(function ($alarm_type, $key) use ($alarm_object) {
			    return $alarm_type->tag_id == $alarm_object->tag_id;
			});
			// if($s == 3) dd($alarm_object->tag_id);

			foreach ($alarm_types_for_tag as $alarm_type) {

				$alarm = new stdClass();

				$alarm->id = $alarm_object->id;
				$alarm->tag_id = $alarm_object->tag_id;
				$alarm->timestamp = $alarm_object->timestamp * 1000;
				if($alarm_type->bytes == 0 && $alarm_type->offset == 0)
					$alarm->active = $value32[0];
				else if($alarm_type->bytes == 0 && $alarm_type->offset != 0) {
					$alarm->active = $value32[$alarm_type->offset - 1] == 1;
				} else if($alarm_type->bytes != 0) {
					$alarm->active = ($value32[0] >> $alarm_type->offset) & $alarm_type->bytes;
				}
				// $alarm->active = $alarm_type->bytes == 0 ? $value32 : ($value32 >> $alarm_type->offset) & $alarm_type->bytes;
				$alarm->type_id = $alarm_type->id;

				array_push($alarms, $alarm);
			}
			$s++;
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
		$machine_id = Machine::select('id')->where('name', $machine_name)->get()->first()->id;

		return $machine_id;
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
			$alarm_types = AlarmType::select('name', 'machine_id', 'tag_id')
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
			$alarm_types = AlarmType::select('name', 'machine_id', 'tag_id')
				->whereIn('machine_id', $machine_ids)
				->get();
		}
		// $alarm_types = DB::select(DB::raw($query));

		$tag_ids = [];
		foreach($alarm_types as $alarm_type) {
			$tag_ids = array_merge($tag_ids, json_decode($alarm_type->tag_id));	
		}

		// $query = 'SELECT device_data.tag_id, device_data.timestamp, device_data.values, device_data.machine_id from device_data WHERE device_data.tag_id IN (' 
		// 			. implode("," , $tag_ids) . ') AND device_data.customer_id = ' . $company_id;

		if ($machine_id) {
			// $query .= ' AND device_data.machine_id = ' . $machine_id;
			$device_data = DeviceData::select('tag_id', 'timestamp', 'values', 'machine_id')
									->whereIn('tag_id', $tag_ids)
									->where('customer_id', $company_id)
									->where('machine_id', $machine_id)
									->get();
		}

		if ($dates) {
			// $query .= ' AND device_data.timestamp BETWEEN ' . strtotime($dates[0]) . ' AND ' . strtotime($dates[1]);
			$device_data = DeviceData::select('tag_id', 'timestamp', 'values', 'machine_id')
									->whereIn('tag_id', $tag_ids)
									->where('customer_id', $company_id)
									->whereBetween('timestamp', [strtotime($dates[0]), strtotime($dates[1])])
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

		$machine_id = $this->getMachineIdByMachineName($request->machine_name);
		$device_data = $this->getBasicDeviceData($request->company_id, $machine_id, $request->dates);
		$alarm_type_names = $this->getAlarmTypesByMachineId($machine_id);

		$data = [0];
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
					$val = $row->values;
				}
				$item->data[] = $val;
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
