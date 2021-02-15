<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class EnergyConsumption extends Model
{
    public $table = 'energy_consumptions';
    public $timestamps = false;
    public $fillable = ['id', 'device_id', 'tag_id', 'timestamp', 'values', 'customer_id', 'machine_id'];
}
