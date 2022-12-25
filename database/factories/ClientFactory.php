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
            'name'=>fake()->name(),
            'id_types_id'=>1,
            'id_num'=>fake()->numerify('######-##-####'),
            'email'=>fake()->safeEmail(),
            'phone_number'=> '',
            'address' => '',
            'created_by' => 1,
        ];
    }
}
