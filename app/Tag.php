<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Tag extends Model
{
    use QueryCacheable;

	protected $cacheFor = 3600;
}
