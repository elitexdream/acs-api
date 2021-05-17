<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmType extends Model
{
    protected $table = 'alarm_types';

    protected $fillable = [
        'name',
        'machine_id',
        'tag_id',
        'bytes',
        'offset'
    ];

    public function device() {
        return $this->belongsTo(Device::class, 'machine_id', 'machine_id');
    }
}
