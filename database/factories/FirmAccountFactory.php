<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FirmAccount>
 */
class FirmAccountFactory extends Factory
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
            'transaction_type'=>"funds in",
            'debit'=>fake()->numerify('###'),
            'credit'=>fake()->numerify('###'),
            'balance'=>fake()->numerify('####'),
            // 'company_id'=>1,
            'bank_account_id'=>1,
        ];
    }
}
