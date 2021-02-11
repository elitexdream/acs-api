<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Tag extends Model
{
    use QueryCacheable;

    // All available tag names
    const NAMES = [
        'DEVICE_TYPE'          => 'device_type',
        'SOFTWARE_VERSION'     => 'software_version',
        'SOFTWARE_BUILD'       => 'software_build',
        'SERIAL_NUMBER_MONTH'  => 'serial_number_month',
        'SERIAL_NUMBER_YEAR'   => 'serial_number_year',
        'SERIAL_NUMBER_UNIT'   => 'serial_number_unit',
        'CAPACITY_UTILIZATION' => 'capacity_utilization',
        'ENERGY_CONSUMPTION'   => 'energy_consumption',
        'RUNNING'              => 'running'
    ];

	protected $cacheFor = 3600;
}
