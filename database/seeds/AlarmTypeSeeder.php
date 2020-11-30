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
	          	'name' => 'Power Loss'

            ], [
	          	'id' => 2,
	          	'name' => 'Out of Material'
	      	], [
	          	'id' => 3,
	          	'name' => 'Hopper Unstable'
	      	], [
				'id' => 4,
				'name' => 'Mixer Failure'
	      	], [
				'id' => 5,
				'name' => 'Unable to make rate'
	      	]
        ];
        DB::table('alarm_types')->insert($alarm_types);
    }
}
