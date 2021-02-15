<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Utilization extends Model
{
    public $table = 'utilizations';
    public $timestamps = false;
    public $fillable = ['id', 'device_id', 'tag_id', 'timestamp', 'values', 'customer_id', 'machine_id'];
}
