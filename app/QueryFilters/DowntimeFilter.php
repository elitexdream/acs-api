<?php


namespace App\QueryFilters;


use Closure;

class DowntimeFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('location') && !request()->has('zone')) {
            return $next($request);
        }

        $builder = $next($request);
        if (request()->has('location') && !request()->has('zone')) {
            return $builder->where('locations.id', request('location'));
        } elseif (request()->has('location') && request()->has('zone')) {
            return $builder->where([
                'locations.id' => request('location'),
                'zones.id' => request('zone')
            ]);
        } elseif (!request()->has('location') && request()->has('zone')) {
            return $builder->where('zones.id', request('zone'));
        } else
            return $builder;

    }

}
