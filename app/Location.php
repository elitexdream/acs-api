<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';

    //TODO: remove customer_id when the column is deleted in DB
    protected $fillable = [
    	'customer_id',
        'name',
        'state',
        'city',
        'zip',
        'company_id'
    ];

    public $timestamps = false;

    public function zones() {
    	return $this->hasMany('App\Zone', 'location_id');
    }
}
