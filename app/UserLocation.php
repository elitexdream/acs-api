<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    protected $table = 'user_locations';

    protected $fillable = [
        'user_id', 'location_id'
    ];
}
