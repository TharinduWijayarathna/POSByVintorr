<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),                     // Random name
            'company' => $this->faker->company(),               // Random company name
            'address' => $this->faker->address(),               // Random address
            'website' => $this->faker->url(),                   // Random website URL
            'contact' => $this->faker->phoneNumber(),           // Random phone number
            'contact2' => $this->faker->optional()->phoneNumber(), // Optional secondary contact
            'contact3' => $this->faker->optional()->phoneNumber(), // Optional tertiary contact
            'email' => $this->faker->unique()->safeEmail(),     // Random email address
            'email2' => $this->faker->optional()->safeEmail(),  // Optional secondary email
            'email3' => $this->faker->optional()->safeEmail(),  // Optional tertiary email
            'status' => $this->faker->boolean(80),              // 80% chance of being true (active)
            'account_no' => $this->faker->bankAccountNumber(),  // Random account number
            'bank_name' => $this->faker->company(),             // Random bank name
            'branch_name' => $this->faker->city(),              // Random branch name
            'type' => $this->faker->randomElement([0, 1]),      // 0: Supplier, 1: Employee
            'created_by' => $this->faker->numberBetween(1, 10), // Random user ID
            'updated_by' => $this->faker->optional()->numberBetween(1, 10), // Optional updated by
        ];
    }
}
