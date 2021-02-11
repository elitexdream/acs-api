<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Device extends Model
{
    protected $fillable = [
        'device_id',
        'name',
        'customer_assigned_name',
        'serial_number',
        'imei',
        'lan_mac_address',
        'iccid',
        'carrier',
        'registered',
        'public_ip_sim',
        'sim_status',
        'location_id',
        'zone_id',
        'checkin'
    ];

    public function configuration() {
        return $this->belongsTo('App\Machine', 'machine_id');
    }

    public function notes() {
        return $this->hasMany('App\Note');
    }

    public function isRunning() {
        if (!$config = $this->configuration) {
            return false;
        }

        $tag = Tag::where('configuration_id', $config->id)
            ->where('tag_name', Tag::NAMES['RUNNING'])
            ->first();

        if ($tag) {
            $running = DB::table('runnings')
                ->where('device_id', $this->serial_number)
                ->where('tag_id', $tag->tag_id)
                ->latest('timestamp')
                ->first();

            if ($running) {
                return json_decode($running->values)[0];
            }
        }

        return false;
    }

    public function teltonikaConfiguration() {
        return $this->hasOne(TeltonikaConfiguration::class, 'teltonika_id', 'serial_number');
    }
}
