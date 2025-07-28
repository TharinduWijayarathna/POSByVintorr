<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $payStatusOptions = array_keys(Invoice::PAY_STATUS);
        $statusOptions = array_keys(Invoice::STATUS);

        return [
            'customer_id' => $this->faker->numberBetween(1, 50),
            'customer_name' => $this->faker->name,
            'customer_address' => $this->faker->address,
            'customer_email' => $this->faker->unique()->safeEmail,
            'customer_mobile' => $this->faker->phoneNumber,
            'customer_company' => $this->faker->company,
            'quotation_id' => $this->faker->numberBetween(1, 20),
            'code' => strtoupper(Str::random(10)),
            'template_id' => $this->faker->numberBetween(1, 5),
            'date' => $this->faker->date(),
            'due_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'remark' => $this->faker->sentence,
            'ref_no' => strtoupper(Str::random(8)),
            'sub_total' => $this->faker->randomFloat(2, 100, 1000),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'discount_type' => 1,
            'discount_value' => 20,
            'other_discount' => $this->faker->randomFloat(2, 0, 50),
            'total' => $this->faker->randomFloat(2, 100, 1000),
            'attention_name' => $this->faker->name,
            'attention_address' => $this->faker->address,
            'is_scheduled' => $this->faker->boolean,
            'schedule_count' => $this->faker->numberBetween(0, 10),
            'schedule_date' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'schedule_type' => 1,
            'status' => $this->faker->randomElement([0, 1]),
            'pay_status' => $this->faker->randomElement([0, 1]),
            'paid_amount' => $this->faker->randomFloat(2, 0, 500),
            'due_amount' => $this->faker->randomFloat(2, 0, 500),
            'temp_due_amount' => $this->faker->randomFloat(2, 0, 500),
            'temp_paid_amount' => $this->faker->randomFloat(2, 0, 500),
            'total_discount' => $this->faker->randomFloat(2, 0, 100),
            'invoice_parameters' => $this->faker->sentence,
            'outstanding_status' => $this->faker->boolean,
            'url_key' => Str::random(32),
            'url_first_view_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'url_last_view_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'url_view_count' => $this->faker->numberBetween(0, 100),
            'refund_amount' => $this->faker->randomFloat(2, 0, 100),
            'temp_refund_amount' => $this->faker->randomFloat(2, 0, 100),
            'created_by' => 1,
            'updated_by' => $this->faker->numberBetween(1, 10),
        ];
    }
}
