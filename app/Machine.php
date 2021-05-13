<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table = 'machines';

    protected $fillable = [
        'name', 'full_json', 'device_type'
    ];

    public $timestamps = false;

    public function downtimePlans() {
    	$this->hasMany('App\DowntimePlan', 'machine_id');
    }
}
