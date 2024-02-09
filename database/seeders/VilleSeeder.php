<?php

namespace Database\Seeders;
use App\Models\Ville;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ville::factory()->create();

        Ville::factory()->Thies()->create();
        Ville::factory()->Mbour()->create();
        Ville::factory()->Ziguinchor()->create();
        Ville::factory()->Bignona()->create();
        Ville::factory()->Guediawaye()->create();
        Ville::factory()->Goree()->create();
    }
}
