<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => "",
            'id_type_id' => 1,
            'id_number' => fake()->numerify('######-##-####'),
            'email' => fake()->safeEmail(),
            'phone_number' => '',
            'address' => '',
            'company_name' => "",
            'company_address' => "",
            'outstanding_balance' => "",
            'linked_client_account' => "",
            'created_by' => 1,
        ];
    }
}
