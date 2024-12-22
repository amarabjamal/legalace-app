<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OperationalCost>
 */
class OperationalCostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'details' => fake()->date,
            'amount' => fake()->numerify('##'),
            'is_recurring' => true,
            'recurring_period' => fake()->name(),
            'is_paid' => true,
            'bank_account_id' => 1,
            'company_id' => "",
            'upload' => "",
            'first_payment_date' => "",
            'no_of_payment' => "",
            'document_number' => "",
        ];
    }
}
