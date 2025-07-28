<?php

namespace Database\Factories;

use App\Models\PosCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosCustomerFactory extends Factory
{
    protected $model = PosCustomer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->unique()->phoneNumber,
            'address' => $this->faker->address,
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'email' => $this->faker->unique()->safeEmail,
            'credit' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
