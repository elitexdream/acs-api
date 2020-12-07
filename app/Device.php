<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SimStatus;

class Device extends Model
{
    protected $fillable = [
        'device_id',
        'name',
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
}
