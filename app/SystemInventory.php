<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemInventory extends Model
{
    protected $table = 'system_inventories';

    protected $fillable = [
        'material_id',
        'location_id',
        'hopper_id',
        'inventory',
        'serial_number',
        'company_id'
    ];
}
