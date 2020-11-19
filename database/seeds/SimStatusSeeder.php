<?php

use Illuminate\Database\Seeder;

class SimStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sim_statuses')->delete();

        $sim_statuses = [
            [
                'id' => 1,
                'name' => 'Not initialized',
            ],
            [
                'id' => 2,
                'name' => 'Active',
            ],
            [
                'id' => 3,
                'name' => 'Suspended',
            ],
            [
                'id' => 4,
                'name' => 'Scrapped',
            ]
        ];
        DB::table('sim_statuses')->insert($sim_statuses);
    }
}