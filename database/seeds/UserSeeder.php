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
        DB::table('oauth_clients')->delete();

        $acs_administrator = [
            'id' => 1,
            'name' => 'ACS Manager',
            'email' => 'acs@gmail.com',
            'verified' => true,
            'password' => bcrypt('password'),
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

        $oauth_clients = [
            [
                'id' => 1,
                'name' => 'Personal Access Client',
                'secret' => 'UQh78CdJMzLCRIahYYkH5Ema7wbJuFjj6OEDxXlm',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'provider' => null,
                'redirect' => 'http://localhost'
            ],
            [
                'id' => 2,
                'name' => 'Password Grant Client',
                'secret' => 'Q1gSfctPvXvxCzh8iIbQ3niH5SUHiSJko0gGo9cy',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'provider' => 'users',
                'redirect' => 'http://localhost'
            ]
        ];

        DB::table('users')->insert($acs_administrator);
        DB::table('profiles')->insert($acs_admin_profile);
        DB::table('oauth_clients')->insert($oauth_clients);
    }
}
