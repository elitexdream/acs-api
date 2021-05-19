<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Device extends Model
{
    protected $table = 'devices';

    protected $fillable = [
        'device_id',
        'name',
        'customer_assigned_name',
        'serial_number',
        'imei',
        'lan_mac_address',
        'iccid',
        'registered',
        'public_ip_sim',
        'sim_status',
        'plc_link',
        'carrier',
        'checkin',
        'tcu_added',
        'plc_ip',
        'hash1',
        'hash2',
        'location_id',
        'zone_id',
        'company_id',
        'machine_id',
    ];

    public function configuration() {
        return $this->belongsTo('App\Machine', 'machine_id');
    }

    public function notes() {
        return $this->hasMany('App\Note');
    }

    public function alarmTypes() {
        return $this->hasMany(AlarmType::class, 'machine_id', 'machine_id');
    }

    public function alarms() {
        return $this->hasMany(Alarm::class, 'machine_id', 'machine_id');
    }

    public function deviceData() {
        return $this->hasMany(DeviceData::class, 'device_id', 'serial_number');
    }

    public function teltonikaConfiguration() {
        return $this->hasOne(TeltonikaConfiguration::class, 'teltonika_id', 'serial_number');
    }

    public function machines() {
        return $this->hasMany(Machine::class, 'id', 'machine_id');
    }

    public function checkin() {
        return $this->hasOne(DeviceCheckin::class, 'device_id', 'serial_number');
    }

    public function isRunning() {
        if (!$config = $this->configuration) {
            return false;
        }

        $tag = Tag::where('configuration_id', $config->id)
            ->where('tag_name', Tag::NAMES['RUNNING'])
            ->first();

        if ($tag) {
            $running = Running::where('device_id', $this->serial_number)
                ->where('tag_id', $tag->tag_id)
                ->latest('timestamp')
                ->first();

            if ($running) {
                return json_decode($running->values)[0];
            }
        }

        return false;
    }

    public function scopeWhereSimActive($query, $filters = [])
    {
        if (is_array($filters) && in_array('active', $filters)) {
            $query->where('sim_status', 'Active');
        }

        return $query;
    }

    public function scopeWhereVisibleOnly($query)
    {
        if ((new Setting())->getTypeVisibleValue() === 'configured') {
            $query->whereIn('serial_number', DeviceConfiguration::all()->pluck('teltonika_id'));
        }

        return $query;
    }

    public function scopeWherePlcLink($query, $filters = [])
    {
        if(is_array($filters) && in_array('PLCLink', $filters)) {
            $query->where('plc_link', true);
        }

        return $query;
    }

    public function scopeWhereRegistered($query, $filters = [])
    {
        if (is_array($filters) && in_array('registered', $filters)) {
            $query->where('registered', true);
        }

        return $query;
    }

    public function scopeWhereSearchQuery($query, $search_query = '')
    {
        if (trim($search_query) !== '') {
            $query->where('name', 'ilike', '%' . $search_query . '%')
                ->orWhere('customer_assigned_name', 'ilike', '%' . $search_query . '%')
                ->orWhere('serial_number', 'ilike', '%' . $search_query . '%');
        }

        return $query;
    }
}
