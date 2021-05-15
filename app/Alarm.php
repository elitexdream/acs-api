<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Alarm extends Model
{
    protected $table = 'alarms';

    protected $fillable = [
        'device_id',
        'tag_id',
        'timestamp',
        'values',
        'machine_id',
        'timedata',
        'serial_number'
    ];

    public function device() {
        return $this->belongsTo(Device::class, 'machine_id', 'machine_id');
    }

    public function machineType() {
        return $this->belongsTo(Machine::class, 'id', 'machine_id');
    }
}
