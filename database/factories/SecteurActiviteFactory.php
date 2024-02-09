<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SecteurActivite>
 */
class SecteurActiviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => 'Dentiste'
        ];
    }

    public function Generaliste(): Factory
    {
        return $this->state([
            'nom' => 'Generaliste',
        ]);
    }

    public function Cardiologue(): Factory
    {
        return $this->state([
            'nom' => 'Cardiologue',
        ]);
    }
    public function Neurologue(): Factory
    {
        return $this->state([
            'nom' => 'Neurologue',
        ]);
    }
    public function Pediatre(): Factory
    {
        return $this->state([
            'nom' => 'Pediatre',
        ]);
    }
}
