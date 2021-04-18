<?php

use Illuminate\Database\Seeder;

class GraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('graphs')->delete();

        $graphs = [
            [
                'machine_id' => 1,
                'graph_id' => 1,
                'graph_name' => 'Capacity Utilization'
            ], [
                'machine_id' => 1,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ], [
                'machine_id' => 1,
                'graph_id' => 3,
                'graph_name' => 'Weight'
            ], [
                'machine_id' => 1,
                'graph_id' => 4,
                'graph_name' => 'Inventories'
            ], [
                'machine_id' => 1,
                'graph_id' => 5,
                'graph_name' => 'Recipe'
            ], [
                'machine_id' => 1,
                'graph_id' => 101,
                'graph_name' => 'Process Rate'
            ], [
                'machine_id' => 1,
                'graph_id' => 102,
                'graph_name' => 'Calibration Factor'
            ], [
                'machine_id' => 1,
                'graph_id' => 103,
                'graph_name' => 'Hopper Stable'
            ], [
                'machine_id' => 1,
                'graph_id' => 104,
                'graph_name' => 'Station Conveying'
            ],

            [
                'machine_id' => 2,
                'graph_id' => 1,
                'graph_name' => 'Capacity Utilization'
            ], [
                'machine_id' => 2,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ], [
                'machine_id' => 2,
                'graph_id' => 3,
                'graph_name' => 'System States'
            ], [
                'machine_id' => 2,
                'graph_id' => 4,
                'graph_name' => 'Feeder Stable'
            ], [
                'machine_id' => 2,
                'graph_id' => 5,
                'graph_name' => 'Process Rate'
            ], [
                'machine_id' => 2,
                'graph_id' => 6,
                'graph_name' => 'Recipe'
            ], [
                'machine_id' => 2,
                'graph_id' => 101,
                'graph_name' => 'Blender Capability'
            ], [
                'machine_id' => 2,
                'graph_id' => 102,
                'graph_name' => 'Target Rate'
            ], [
                'machine_id' => 2,
                'graph_id' => 103,
                'graph_name' => 'Feeder Calibration'
            ], [
                'machine_id' => 2,
                'graph_id' => 104,
                'graph_name' => 'Feeder Speed'
            ],

            [
                'machine_id' => 3,
                'graph_id' => 1,
                'graph_name' => 'Capacity Utilization'
            ], [
                'machine_id' => 3,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ], [
                'machine_id' => 3,
                'graph_id' => 3,
                'graph_name' => 'System States'
            ], [
                'machine_id' => 3,
                'graph_id' => 4,
                'graph_name' => 'Accumulated Hopper Inventory'
            ], [
                'machine_id' => 3,
                'graph_id' => 5,
                'graph_name' => 'Accumulated Hauloff Length'
            ],

            [
                'machine_id' => 4,
                'graph_id' => 1,
                'graph_name' => 'Capability Utilization'
            ], [
                'machine_id' => 4,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ],

            [
                'machine_id' => 5,
                'graph_id' => 1,
                'graph_name' => 'Capability Utilization'
            ], [
                'machine_id' => 5,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ], [
                'machine_id' => 5,
                'graph_id' => 3,
                'graph_name' => 'Pump Hours'
            ], [
                'machine_id' => 5,
                'graph_id' => 4,
                'graph_name' => 'Pump Hours Oil Change'
            ], [
                'machine_id' => 5,
                'graph_id' => 5,
                'graph_name' => 'Online Life'
            ], [
                'machine_id' => 5,
                'graph_id' => 101,
                'graph_name' => 'Pumps Online States'
            ], [
                'machine_id' => 5,
                'graph_id' => 102,
                'graph_name' => 'Pumps Blowback Engaged'
            ],

            [
                'machine_id' => 6,
                'graph_id' => 1,
                'graph_name' => 'Capability Utilization'
            ], [
                'machine_id' => 6,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ], [
                'machine_id' => 6,
                'graph_id' => 3,
                'graph_name' => 'Drying Hopper States'
            ], [
                'machine_id' => 6,
                'graph_id' => 4,
                'graph_name' => 'Hopper Air Temperature'
            ], [
                'machine_id' => 6,
                'graph_id' => 101,
                'graph_name' => 'Bed States'
            ], [
                'machine_id' => 6,
                'graph_id' => 102,
                'graph_name' => 'DH Online Hours'
            ], [
                'machine_id' => 6,
                'graph_id' => 103,
                'graph_name' => 'Dryer Online Hours'
            ], [
                'machine_id' => 6,
                'graph_id' => 104,
                'graph_name' => 'Blower Run Hours'
            ],

            [
                'machine_id' => 7,
                'graph_id' => 1,
                'graph_name' => 'Capability Utilization'
            ], [
                'machine_id' => 7,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ],

            [
                'machine_id' => 8,
                'graph_id' => 1,
                'graph_name' => 'Capability Utilization'
            ], [
                'machine_id' => 8,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ],

            [
                'machine_id' => 9,
                'graph_id' => 1,
                'graph_name' => 'Capability Utilization'
            ], [
                'machine_id' => 9,
                'graph_id' => 2,
                'graph_name' => 'Energy Consumption'
            ], [
                'machine_id' => 9,
                'graph_id' => 3,
                'graph_name' => 'Process Out Temperature'
            ],

            [
                'machine_id' => 11,
                'graph_id' => 1,
                'graph_name' => 'Machine States'
            ], [
                'machine_id' => 11,
                'graph_id' => 2,
                'graph_name' => 'TCU Temperature'
            ], [
                'machine_id' => 11,
                'graph_id' => 3,
                'graph_name' => 'Capability Utilization'
            ]
        ];
        
        DB::table('graphs')->insert($graphs);
    }
}
