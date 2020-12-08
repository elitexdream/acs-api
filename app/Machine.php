<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'name'
    ];

    public function notes() {
    	return $this->hasMany('App\Note');
    }

    public function downtimePlans() {
    	$this->hasMany('App\DowntimePlan', 'machine_id');
    }
}
