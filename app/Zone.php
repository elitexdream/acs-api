<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    public $fillable = [
    	'name', 'location_id'
    ];
}
