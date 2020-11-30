<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('profiles')->delete();

        $acs_administrator = [
            'id' => 1,
            'name' => 'ACS Manager',
            'email' => 'acsdev@acsgroup.com',
            'verified' => true,
            'password' => bcrypt('gJwsEqpdYjbDFapAH4nVTstW'),
        ];

        $acs_admin_profile = [
            'id' => 1,
            'user_id' => 1,
            'address_1' => '',
            'address_2' => '',
            'zip' => '',
            'state' => '',
            'city' => '',
            'country' => '',
            'phone' => '',
        ];

        DB::table('users')->insert($acs_administrator);
        DB::table('profiles')->insert($acs_admin_profile);
    }
}
