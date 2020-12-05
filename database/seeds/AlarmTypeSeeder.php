<?php

use Illuminate\Database\Seeder;

class AlarmTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alarm_types')->delete();

        $alarm_types = [
            [
	          	'name' => 'Power Loss',
                'machine_id' => 1,
                'tag_id' => [27]
            ], [
	          	'name' => 'Out of Material',
                'machine_id' => 1,
                'tag_id' => [28]
	      	], [
	          	'name' => 'Hopper Unstable',
                'machine_id' => 1,
                'tag_id' => [31, 32, 33, 34, 35, 36, 37, 38]
	      	], [
                'name' => 'Hopper Overfeed',
                'machine_id' => 1,
                'tag_id' => [39]
            ], [
                'name' => 'Hopper Over Max',
                'machine_id' => 1,
                'tag_id' => 40
            ], [
				'name' => 'Unable to make rate',
                'machine_id' => 1,
                'tag_id' => [43]
	      	], [
                'name' => 'Max Empty Weight',
                'machine_id' => 1,
                'tag_id' => [41]
            ], [
                'name' => 'Pumper Starter Fault',
                'machine_id' => 1,
                'tag_id' => [44]
            ], [
                'name' => 'Mixer Failure',
                'machine_id' => 1,
                'tag_id' => [42]
            ], [
                'name' => 'System Not Stable',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'No Extruder Flow',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Extruder Drive Failure',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Hauloff Drive Failure',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Extruder Underspeed',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Extruder Overspeed',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Hauloff Underspeed',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Hauloff Overspeed',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Hopper Over Max Flow',
                'machine_id' => 3,
                'tag_id' => [30]
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => [28, 29, 30, 31, 32, 33]
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => [34, 35, 36, 37, 38, 39]
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => [41, 42, 43, 44, 45, 46]
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => [47, 48, 49, 50, 51, 52]
            ], [
                'name' => 'Unable to make rate',
                'machine_id' => 2,
                'tag_id' => [53]
            ], [
                'name' => 'Bad Blend',
                'machine_id' => 2,
                'tag_id' => [54]
            ], [
                'name' => 'Blender Rate Out Of Range',
                'machine_id' => 2,
                'tag_id' => [55]
            ], [
                'name' => 'System Not Stable',
                'machine_id' => 4,
                'tag_id' => [30]
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 4,
                'tag_id' => [30]
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 4,
                'tag_id' => [30]
            ], [
                'name' => 'No Feeder Flow',
                'machine_id' => 4,
                'tag_id' => [30]
            ], [
                'name' => 'Feeder Under Speed',
                'machine_id' => 4,
                'tag_id' => [30]
            ], [
                'name' => 'Pump Oil Change Required',
                'machine_id' => 5,
                'tag_id' => [18]
            ], [
                'name' => 'Power Loss While Running',
                'machine_id' => 5,
                'tag_id' => [19]
            ], [
                'name' => 'Pump Starter Fault',
                'machine_id' => 5,
                'tag_id' => [20]
            ], [
                'name' => 'Vacuum Levels Option',
                'machine_id' => 5,
                'tag_id' => [21]
            ]
        ];
        DB::table('alarm_types')->insert($alarm_types);
    }
}
