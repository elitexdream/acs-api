<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $table = 'zones';

    //TODO: remove customer_id when the column is deleted in DB
    protected $fillable = [
    	'name', 'location_id', 'customer_id', 'company_id'
    ];

    public $timestamps = false;

    public function location() {
    	return $this->belongsTo('App\Location', 'location_id');
    }
}
