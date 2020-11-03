<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\Profile;

class UserTest extends TestCase
{
    /**
     * test to see if a user has a profile
     *
     * @return void
     */
    public function test_a_user_has_a_profile()
    {
    	$user = factory(User::class)->create();
    	$profile = factory(Profile::class)->create(['user_id' => $user->id]);

    	$this->assertInstanceOf(Profile::class, $user->profile);

    	$this->assertEquals(1, $user->profile->count());
    }
}
