<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryMaterial extends Model
{
    public $table = 'inventory_materials';

    public $fillable = [
    	'plc_id',
    	'material1_id',
    	'material2_id',
    	'material3_id',
    	'material4_id',
    	'material5_id',
    	'material6_id',
        'material7_id',
        'material8_id',
    	'location1_id',
    	'location2_id',
    	'location3_id',
    	'location4_id',
    	'location5_id',
    	'location6_id',
        'location7_id',
        'location8_id'
    ];
}
