<?php


namespace App\QueryFilters;


class Sort extends Filter
{
    protected function applyFilter($builder)
    {
        switch (request('sort')){
            case 'location':
                $sort = 'locations.name';
                break;
            case 'start_time':
                $sort = 'start_time';
                break;
            case 'end_time':
                $sort = 'end_time';
                break;
            case 'type':
                $sort = 'downtime_type_name';
                break;
            case 'zone':
                $sort = 'zone_name';
                break;
            case 'reason_id':
                $sort = 'downtime_reason';
                break;
            case 'comment':
                $sort = 'comment';
                break;
            default:
                $sort = 'machines.name';
                break;
        }
        return $builder->orderBy($sort, request('order'));
    }
}
