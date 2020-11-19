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
                'name' => 'Zone 1',
            ],
            [
                'id' => 2,
                'name' => 'Zone 2',
            ],
            [
                'id' => 3,
                'name' => 'Zone 3',
            ],
            [
                'id' => 4,
                'name' => 'Zone 4',
            ],
            [
                'id' => 5,
                'name' => 'Zone 5',
            ]
        ];
        DB::table('zones')->insert($zones);
    }
}
