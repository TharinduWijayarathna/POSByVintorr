<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BillPayment>
 */
class BillPaymentFactory extends Factory
{
    public function definition()
    {
        return [
            'created_by' => 1,
            'customer_id' => $this->faker->randomNumber(1, 100),
            'code' => $this->faker->unique()->regexify('[A-Z]{5}[0-9]{3}'),
            'order_id' => $this->faker->randomNumber(1, 1000),
            'order_total' => $this->faker->randomFloat(2, 50, 1000),
            'paid_amount' => $this->faker->randomFloat(2, 0, 1000),
            'balance' => $this->faker->randomFloat(2, 0, 500),
            'remark' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'status' => $this->faker->randomElement([0, 1]), // 0 for 'CREDIT_BILL', 1 for 'DONE'
            'change' => $this->faker->randomFloat(2, 0, 200),
            'accepted_amount' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
