<?php

namespace App\Exports;

use App\Exports\MachinesReportExport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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

        foreach ($this->data as $key => $item) {
            $sheets[] = new MachinesReportExport($item);
        }

        return $sheets;
    }
}
