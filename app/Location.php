<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
    	'customer_id',
        'name',
        'state',
        'city',
        'zip'
    ];

    public $timestamps = false;

    public function zones() {
    	return $this->hasMany('App\Zone', 'location_id');
    }
}
