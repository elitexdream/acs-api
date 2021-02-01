<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TeltonikaConfiguration extends Model
{
    public $table = 'device_configurations';

    public function plcMachine() {
    	return Machine::where('device_type', $this->plc_type)->first();
    }

    public function isPlcConnected() {
    	return $this->plc_status;
    }

    public function isTcuConnected() {
    	return $this->tcu_status;
    }

    public function plcSerialNumber() {
    	return $this->plc_serial_number;
    }

    public function tcuSerialNumber() {
    	return $this->tcu_serial_number;
    }

    public function plcAnalyticsGraphs() {
		$machine_id = $this->plcMachine()->id;
		return DB::table('graphs')->where('machine_id', $machine_id)->where('graph_id', '<', 100)->get();
    }

    public function plcPropertiesGraphs() {
		$machine_id = $this->plcMachine()->id;
		return DB::table('graphs')->where('machine_id', $machine_id)->where('graph_id', '>', 100)->get();
    }

    public function tcuAnalyticsGraphs() {
		return DB::table('graphs')->where('machine_id', 11)->where('graph_id', '<', 100)->get();
    }

    public function tcuPropertiesGraphs() {
		return DB::table('graphs')->where('machine_id', 11)->where('graph_id', '>', 100)->get();
    }

    public function plcEnabledAnalyticsGraphs($user_id, $serial_number) {
		$machine_id = $this->plcMachine()->id;
		$v = DB::table('enabled_properties')->where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return DB::table('graphs')->where('machine_id', $machine_id)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '<', 100)->get();
		}
		else
			return [];
    }

    public function plcEnabledPropertiesGraphs($user_id, $serial_number) {
		$machine_id = $this->plcMachine()->id;
		$v = DB::table('enabled_properties')->where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return DB::table('graphs')->where('machine_id', $machine_id)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '>', 100)->get();
		}
		else
			return [];
    }

    public function tcuEnabledAnalyticsGraphs($user_id, $serial_number) {
		$v = DB::table('enabled_properties')->where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return DB::table('graphs')->where('machine_id', 11)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '<', 100)->get();
		}
		else
			return [];
    }

    public function tcuEnabledPropertiesGraphs($user_id, $serial_number) {
		$v = DB::table('enabled_properties')->where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return DB::table('graphs')->where('machine_id', 11)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '>', 100)->get();
		}
		else
			return [];
    }
}
