<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserZone extends Model
{
    protected $table = 'user_zones';

    protected $fillable = [
        'user_id', 'zone_id'
    ];
}
