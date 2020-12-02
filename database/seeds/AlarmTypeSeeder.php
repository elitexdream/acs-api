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
          		'id' => 1,
	          	'name' => 'Power Loss',
                'machine_id' => 1,
            ], [
	          	'id' => 2,
	          	'name' => 'Out of Material',
                'machine_id' => 1,
	      	], [
	          	'id' => 3,
	          	'name' => 'Hopper Unstable',
                'machine_id' => 1,
	      	], [
				'id' => 4,
				'name' => 'Mixer Failure',
                'machine_id' => 1,
	      	], [
				'id' => 5,
				'name' => 'Unable to make rate',
                'machine_id' => 1,
	      	], [
                'id' => 6,
                'name' => 'System Not Stable',
                'machine_id' => 3,
            ], [
                'id' => 7,
                'name' => 'Out Of Material',
                'machine_id' => 3,
            ], [
                'id' => 8,
                'name' => 'Load Cell Failure',
                'machine_id' => 3,
            ], [
                'id' => 9,
                'name' => 'No Extruder Flow',
                'machine_id' => 3,
            ], [
                'id' => 10,
                'name' => 'Extruder Drive Failure',
                'machine_id' => 3,
            ], [
                'id' => 11,
                'name' => 'Hauloff Drive Failure',
                'machine_id' => 3,
            ], [
                'id' => 12,
                'name' => 'Extruder Underspeed',
                'machine_id' => 3,
            ], [
                'id' => 13,
                'name' => 'Extruder Overspeed',
                'machine_id' => 3,
            ], [
                'id' => 14,
                'name' => 'Hauloff Underspeed',
                'machine_id' => 3,
            ], [
                'id' => 15,
                'name' => 'Hauloff Overspeed',
                'machine_id' => 3,
            ], [
                'id' => 16,
                'name' => 'Hopper Over Max Flow',
                'machine_id' => 3,
            ], [
                'id' => 17,
                'name' => 'Out Of Material',
                'machine_id' => 2,
            ], [
                'id' => 18,
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
            ], [
                'id' => 19,
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
            ], [
                'id' => 20,
                'name' => 'No Flow',
                'machine_id' => 2,
            ], [
                'id' => 21,
                'name' => 'Unable to make rate',
                'machine_id' => 2,
            ], [
                'id' => 22,
                'name' => 'Bad Blend',
                'machine_id' => 2,
            ], [
                'id' => 23,
                'name' => 'Blender Rate Out Of Range',
                'machine_id' => 2,
            ]
        ];
        DB::table('alarm_types')->insert($alarm_types);
    }
}
