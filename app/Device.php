<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SimStatus;

class Device extends Model
{
    protected $fillable = [
        'serial_number',
        'imei',
        'lan_mac_address',
        'iccid',
        'registered',
        'public_ip_sim',
        'sim_status',
        'location_id',
        'zone_id'
    ];

    public function simStatus() {
    	return $this->belongsTo('App\SimStatus');
    }
}
