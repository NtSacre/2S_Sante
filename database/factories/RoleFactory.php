<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
     /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => 'admin',
        ];
    }

    /**
     * Define a state for the 'medecin' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function medecin(): Factory
    {
        return $this->state([
            'nom' => 'medecin',
        ]);
    }

    /**
     * Define a state for the 'patient' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function patient(): Factory
    {
        return $this->state([
            'nom' => 'patient',
        ]);
    }
}
