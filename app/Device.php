<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SimStatus;

class Device extends Model
{
    protected $fillable = [
        'serial_number', 'imei', 'lan_mac_address', 'iccid', 'registered', 'public_ip_sim', 'sim_status'
    ];

    public function simStatus() {
    	return $this->belongsTo('App\SimStatus');
    }

    public function setSimStatus($status_string) {
    	$status = SimStatus::where('name', $status_string)->first();

    	$this->sim_status = $status->id;

    	$this->save();
    }
}
