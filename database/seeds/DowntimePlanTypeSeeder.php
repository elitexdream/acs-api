<?php

use Illuminate\Database\Seeder;

class DowntimePlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('downtime_plan_types')->delete();
        $downtime_plan_types = [
            [
                'id' => 1,
                'name' => 'No Demand',
            ], [
                'id' => 2,
                'name' => 'Preventative Maintenance',
            ], [
                'id' => 3,
                'name' => 'Machine Failure',
            ], [
                'id' => 4,
                'name' => 'Power Outage',
            ], [
                'id' => 5,
                'name' => 'Idle',
            ]
        ];
        DB::table('downtime_plan_types')->insert($downtime_plan_types);
    }
}
