<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\DeviceData;

class MachinesReportExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    use Exportable;
    private $data;

    public function __construct($request) {
        $this->data = $request;
    }

    public function array(): array
    {
        $tag_groups = collect($this->data['tags'])->groupBy('tag_id')->toArray();

        return json_decode(json_encode(
            DeviceData::where('machine_id', $this->data['machine']['machine_id'])
                ->where('device_id', $this->data['machine']['serial_number'])
                ->whereIn('tag_id', collect($this->data['tags'])->pluck('tag_id'))
                ->where('timestamp', '>', $this->data['from'])
                ->where('timestamp', '<', $this->data['to'])
                ->orderBy('tag_id')
                ->orderBy('timestamp')
                ->get()
                ->map(function($object, $key) use ($tag_groups){

                    $tag = $tag_groups[$object->tag_id][0];

                    $divide_by = isset($tag['divided_by']) ? $tag['divided_by'] : 1;
                    $offset = isset($tag['offset']) ? $tag['offset'] : 0;
                    $bytes = isset($tag['bytes']) ? $tag['bytes'] : 0;

                    if ($bytes) {
                        $value = ((json_decode($object->values)[0] >> $tag['offset']) & $tag['bytes']);
                    } else {
                        $value = json_decode($object->values)[$offset] / $divide_by;
                    }

                    return [$object->timedata, $tag['name'], round($value, 3)];
                })
                ->toArray()
        ), true);
    }

    public function headings(): array
    {
        return [
            'Timestamp',
            'Tag Name',
            'Value',
        ];
    }

    public function title(): string
    {
        return $this->data['machine']['name'];
    }
}
