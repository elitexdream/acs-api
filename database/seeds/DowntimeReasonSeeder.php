<?php

use Illuminate\Database\Seeder;

class DowntimeReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('downtime_reasons')->delete();
        $downtime_reasons = [
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
                'name' => 'Other',
            ], [
                'id' => 6,
                'name' => 'Change Over',
            ]
        ];
        DB::table('downtime_reasons')->insert($downtime_reasons);
    }
}
