<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ville>
 */
class VilleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => 'Dakar'
        ];
    }

    public function Thies(): Factory
    {
        return $this->state([
            'nom' => 'Thies',
        ]);
    }

    public function Mbour(): Factory
    {
        return $this->state([
            'nom' => 'Mbour',
        ]);
    }
    public function Ziguinchor(): Factory
    {
        return $this->state([
            'nom' => 'Ziguinchor',
        ]);
    }
    public function Bignona(): Factory
    {
        return $this->state([
            'nom' => 'Bignona',
        ]);
    }
    public function Guediawaye(): Factory
    {
        return $this->state([
            'nom' => 'Guediawaye',
        ]);
    }
    public function Goree(): Factory
    {
        return $this->state([
            'nom' => 'Goree',
        ]);
    }
}
