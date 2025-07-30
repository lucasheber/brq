<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'    => $this->faker->randomElement(User::pluck('id')->toArray()),
            'document'   => $this->faker->randomElement([$this->cpf(), $this->cnpj()]),
            'amount'     => $this->faker->randomFloat(2, 100, 10000), // Amount in dollars
            'description' => $this->faker->sentence, // New description field
            'currency'   => $this->faker->randomElement(['USD', 'EUR', 'BRL']),
            'status'     => $this->faker->randomElement(\App\Enums\TransactionStatus::cases())->value,
            'location'   => $this->faker->city, // Assuming location is a city name
            'created_at' => $this->faker->dateTimeBetween('-10 days', 'now'),
        ];
    }

    private function cpf(): string
    {
        // Generate a random CPF number
        return $this->faker->numerify('###.###.###-##');
    }

    private function cnpj(): string
    {
        // Generate a random CNPJ number
        return $this->faker->numerify('##.###.###/####-##');
    }
}
