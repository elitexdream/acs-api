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

        $acs_super_admin = [
            'id' => 1,
            'name' => 'ACS Super Admin',
            'email' => 'acssuper@acsgroup.com',
            'verified' => true,
            'password' => bcrypt('gJwsEqpdYjbDFapAH4nVTstW'),
        ];

        $acs_administrator = [
            'id' => 2,
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

        $acs_super_admin_profile = [
            'id' => 2,
            'user_id' => 2,
            'address_1' => '',
            'address_2' => '',
            'zip' => '',
            'state' => '',
            'city' => '',
            'country' => '',
            'phone' => '',
        ];

        DB::table('users')->insert($acs_administrator);
        DB::table('users')->insert($acs_super_admin);
        DB::table('profiles')->insert($acs_admin_profile);
        DB::table('profiles')->insert($acs_super_admin_profile);

        // DB::update('SELECT setval('users_id_seq', COALESCE((SELECT MAX(id)+1 FROM users), 1), false);');
        // DB::update('SELECT setval('profiles_id_seq', COALESCE((SELECT MAX(id)+1 FROM profiles), 1), false);');
    }
}