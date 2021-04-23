<?php

namespace App\Exports;

use App\Exports\MachinesReportExport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Device;

class MachinesReportSheetExport implements WithMultipleSheets
{
    use Exportable;

    private $data;

    public function __construct($request) {
        $this->data = $request;
    }

    public function sheets(): array
    {
        $sheets = [];
        $from = strtotime($this->data->timeRange['dateFrom'] . ' ' . $this->data->timeRange['timeFrom']);
        $to = strtotime($this->data->timeRange['dateTo'] . ' ' . $this->data->timeRange['timeTo']);

        foreach ($this->data->machineTags as $device_id => $tags) {

            $machine = Device::where('device_id', $device_id)->first();

            $sheets[] = new MachinesReportExport([
                'tags' => $tags,
                'machine' => $machine->toArray(),
                'from' => $from,
                'to' => $to,
            ]);
        }

        return $sheets;
    }
}
