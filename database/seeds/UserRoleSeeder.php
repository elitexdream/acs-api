<?php

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->delete();

        $user_roles = [
        	[
	            'user_id' => 1,
	            'role_id' => ROLE_SUPER_ADMIN,
        	], [
                'user_id' => 2,
                'role_id' => ROLE_ACS_ADMIN,
            ]
        ];
        DB::table('user_roles')->insert($user_roles);
    }
}
