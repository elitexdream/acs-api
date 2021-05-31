<?php

use Illuminate\Database\Seeder;

class DowntimeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('downtime_type')->delete();
        $downtime_types = [
            [
                'id' => 1,
                'name' => 'Unplanned',
            ], [
                'id' => 2,
                'name' => 'Idle',
            ], [
                'id' => 3,
                'name' => 'Planned',
            ]
        ];
        DB::table('downtime_type')->insert($downtime_types);
    }
}
