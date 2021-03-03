<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromArray
{
	// private $data;

	public function __construct($request)
    {
        // $this->data = $request;
    }

	public function array(): array
    {
    	return [
            [1, 2, 3],
            [4, 5, 6]
        ];
        // return array_map(function($d) {
    		// return [[
    		// 	'material',
    		// 	'location',
    		// 	'value'
    		// ]];
    	// }, $this->data);
    }

}