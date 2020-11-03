<?php

namespace Tests\Unit;

use Tests\TestCase;

class CustomerTest extends TestCase
{
    /**
     * A create test.
     *
     * @return void
     */
    public function test_can_create_customer() {
        $data = [
            'company_name' => $this->faker->name,
            'administrator_name' => $this->faker->name,
            'administrator_email' => $this->faker->email,
        ];

        $this->post(route('customers.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }
}
