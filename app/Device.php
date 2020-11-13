<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial_number', 'imei', 'lan_mac_address', 'iccid', 'registered', 'public_ip_sim'
    ];
}
