<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountReporting>
 */
class AccountReportingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date'=>fake()->date,
            'description'=>"",
            'client'=>"none",
            'debit'=>fake()->numerify('###'),
            'credit'=>fake()->numerify('###'),
            'balance'=>fake()->numerify('####'),
        ];
    }
}
