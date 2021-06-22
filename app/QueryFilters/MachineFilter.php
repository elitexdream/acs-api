<?php


namespace App\QueryFilters;


use Closure;

class MachineFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('machine_id') && !request()->has('serial_number')) {
            return $next($request);
        }
        $builder = $next($request);

        $builder->where(['devices.machine_id' => request('machine_id'),
            'devices.serial_number' => request('serial_number')
        ]);

        return $builder;

    }
}
