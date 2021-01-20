<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Alarm extends Model
{
	use QueryCacheable;

    protected $cacheFor = 600;
    
    public $fillable = ['tag_id', 'machine_id', 'device_id'];
}
