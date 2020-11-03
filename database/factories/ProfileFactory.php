<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
    	'address_1' => $this->faker->address,
		'address_2' => $this->faker->address,
		'zip' => $this->faker->postcode,
		'state' => $this->faker->state,
		'city' => $this->faker->city,
		'country' => $this->faker->country,
		'phone' => $this->faker->phoneNumber,
		'user_id' => 1,
    ];
});
