<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class TeltonikaConfiguration extends Model
{
    protected $table = 'device_configurations';

    protected $fillable = [
        'teltonika_id',
        'plc_type',
        'plc_serial_number',
        'plc_status',
        'tcu_type',
        'tcu_serial_number',
        'tcu_status',
        'body'
    ];

    public function device() {
        return $this->belongsTo(Device::class, 'serial_number', 'teltonika_id');
    }

    public function alarms() {
        return $this->hasMany(Alarm::class, 'serial_number', 'plc_serial_number');
    }

    public function machineBySerialNumber($serial_number) {
    	if($this->plc_serial_number == $serial_number)
    		return Machine::where('device_type', $this->plc_type)->first();
    	else if($this->tcu_serial_number == $serial_number)
    		return Machine::where('id', MACHINE_TRUETEMP_TCU)->first();
    	else
    		return null;
    }

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
		return Graph::where('machine_id', $machine_id)->where('graph_id', '<', 100)->get();
    }

    public function plcPropertiesGraphs() {
		$machine_id = $this->plcMachine()->id;
		return Graph::where('machine_id', $machine_id)->where('graph_id', '>', 100)->get();
    }

    public function tcuAnalyticsGraphs() {
		return Graph::where('machine_id', MACHINE_TRUETEMP_TCU)->where('graph_id', '<', 100)->get();
    }

    public function tcuPropertiesGraphs() {
		return Graph::where('machine_id', MACHINE_TRUETEMP_TCU)->where('graph_id', '>', 100)->get();
    }

    public function plcEnabledAnalyticsGraphs($user_id, $serial_number) {
		$machine_id = $this->plcMachine()->id;
		$v = EnabledProperty::where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return Graph::where('machine_id', $machine_id)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '<', 100)->get();
		}
		else
			return Graph::where('machine_id', $machine_id)->where('graph_id', '<', 100)->get();
    }

    public function plcEnabledPropertiesGraphs($user_id, $serial_number) {
		$machine_id = $this->plcMachine()->id;
		$v = EnabledProperty::where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return Graph::where('machine_id', $machine_id)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '>', 100)->get();
		}
		else
			return Graph::where('machine_id', $machine_id)->where('graph_id', '>', 100)->get();
    }

    public function tcuEnabledAnalyticsGraphs($user_id, $serial_number) {
		$v = EnabledProperty::where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return Graph::where('machine_id', MACHINE_TRUETEMP_TCU)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '<', 100)->get();
		}
		else
			return Graph::where('machine_id', MACHINE_TRUETEMP_TCU)->where('graph_id', '<', 100)->get();
    }

    public function tcuEnabledPropertiesGraphs($user_id, $serial_number) {
		$v = EnabledProperty::where('user_id', $user_id)->where('serial_number', $serial_number)->first();

		if($v) {
			return Graph::where('machine_id', MACHINE_TRUETEMP_TCU)->whereIn('graph_id', json_decode($v->property_ids))->where('graph_id', '>', 100)->get();
		}
		else
			return Graph::where('machine_id', MACHINE_TRUETEMP_TCU)->where('graph_id', '>', 100)->get();
    }
}
