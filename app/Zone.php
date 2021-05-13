<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';

    protected $fillable = [
    	'name', 'location_id', 'customer_id'
    ];

    public $timestamps = false;

    public function location() {
    	return $this->belongsTo('App\Location', 'location_id');
    }
}
