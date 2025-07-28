<?php

declare(strict_types = 1);

namespace Database\Factories;

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
            'document' => $this->faker->randomElement([$this->cpf(), $this->cnpj()]),
            'amount'   => $this->faker->randomFloat(2, 100, 10000), // Amount in dollars
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'BRL']),
            'status'   => \App\Enums\TransactionStatus::PENDING->value,
            'location' => $this->faker->city, // Assuming location is a city name
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
