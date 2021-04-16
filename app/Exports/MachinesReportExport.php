<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class MachinesReportExport implements FromArray, WithHeadings, WithTitle
{
    private $data;

    public function __construct($request) {
        $this->data = $request;
    }

    public function array(): array
    {
        return array_map(function($d) {
    		return [
    			$d[0],
    			$d[2],
    			$d[1]
    		];
    	}, $this->data->tags);
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
        return $this->data->machine_name;
    }
}
