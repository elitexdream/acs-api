<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    public $table = 'thresholds';

    protected $fillable = [
        'user_id',
        'device_id',
        'tag_id',
        'operator',
        'value',
        'sms_info',
        'email_info',
        'status',
        'offset',
        'serial_number',
        'multipled_by',
        'bytes',
        'last_triggered_at',
        'message_status'
    ];
}
