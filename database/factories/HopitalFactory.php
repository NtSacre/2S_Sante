<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hopital>
 */
class HopitalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => 'De La Paix'
        ];
    }

    public function Fann(): Factory
    {
        return $this->state([
            'nom' => 'Fann',
        ]);
    }


    public function GrandDakar(): Factory
    {
        return $this->state([
            'nom' => 'Gaspard Camara',
        ]);
    }

    public function Principal(): Factory
    {
        return $this->state([
            'nom' => 'DANTEC',
        ]);
    }

    public function GrandYoff(): Factory
    {
        return $this->state([
            'nom' => 'Idrissa Pouye',
        ]);
    }
}
