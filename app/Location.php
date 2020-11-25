<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	public $timestamps = false;
    public $fillable = [
    	'name', 'state', 'city', 'zip'
    ];
}
