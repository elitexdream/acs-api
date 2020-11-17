<?php

use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zones')->delete();

        $zones = [
            [
                'id' => 1,
                'zone_name' => 'Zone 1',
            ],
            [
                'id' => 2,
                'zone_name' => 'Zone 2',
            ],
            [
                'id' => 3,
                'zone_name' => 'Zone 3',
            ],
            [
                'id' => 4,
                'zone_name' => 'Zone 4',
            ],
            [
                'id' => 5,
                'zone_name' => 'Zone 5',
            ]
        ];
        DB::table('zones')->insert($zones);
    }
}
