<?php

namespace App\Imports;

use App\Device;
use Maatwebsite\Excel\Concerns\ToModel;

class DevicesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Device([
           'serial_number' => $row[0],
           'imei' => $row[1], 
           'lan_mac_address' => $row[2],
           'iccid' => $row[3]
        ]);
    }
}
