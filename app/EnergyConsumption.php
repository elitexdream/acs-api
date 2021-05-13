<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class EnergyConsumption extends Model
{
    protected $table = 'energy_consumptions';

    protected $fillable = [
        'device_id',
        'tag_id',
        'timestamp',
        'values',
        'machine_id',
        'serial_number',
        'timedata',
        'customer_id'
    ];

    public $timestamps = false;
}
