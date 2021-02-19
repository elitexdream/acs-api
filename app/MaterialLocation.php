<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialLocation extends Model
{
	public $table = 'material_locations';
    protected $fillable = [
        'location'
    ];
}
