<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('roles')->delete();

        $roles = [
            [
                'id' => 1,
                'key' => 'acs_admin',
                'name' => 'ACS Administrator',
            ], [
                'id' => 2,
                'key' => 'acs_manager',
                'name' => 'ACS Manager',
            ], [
                'id' => 3,
                'key' => 'acs_viewer',
                'name' => 'ACS Viewer',
            ], [
                'id' => 4,
                'key' => 'customer_admin',
                'name' => 'Customer Administrator',
            ], [
                'id' => 5,
                'key' => 'customer_manager',
                'name' => 'Customer Manager',
            ], [
                'id' => 6,
                'key' => 'customer_operator',
                'name' => 'Customer Operator',
            ]
        ];
        DB::table('roles')->insert($roles);
    }
}
