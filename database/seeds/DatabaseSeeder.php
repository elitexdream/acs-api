<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sim_statuses')->delete();
        
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(MachineSeeder::class);
        // $this->call(ZoneSeeder::class);
        // $this->call(SimStatusSeeder::class);


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
