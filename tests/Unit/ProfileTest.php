<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Profile;

class ProfileTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_profile_belongs_to_a_user()
    {
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->create(['user_id' => $user->id]); 

        $this->assertInstanceOf(User::class, $profile->user);
    }
}
