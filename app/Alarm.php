<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Alarm extends Model
{
    public $fillable = ['tag_id', 'machine_id', 'device_id'];
}
