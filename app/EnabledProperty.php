<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnabledProperty extends Model
{
    protected $table = 'enabled_properties';

    protected $fillable = [
        'user_id', 'serial_number', 'property_ids'
    ];
}
