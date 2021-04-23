<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    public $table = 'thresholds';

    protected $fillable = [
        'user_id', 'device_id', 'tag_id', 'operator', 'value', 'sms_info', 'email_info', 'status', 'offset'
    ];
}
