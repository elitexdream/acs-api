<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
    
    public function downtimePlans() {
    	$this->hasMany('App\DowntimePlan', 'machine_id');
    }
}
