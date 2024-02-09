<?php

namespace Database\Seeders;
use App\Models\Hopital;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HopitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hopital::factory()->create();

        Hopital::factory()->Fann()->create();

        Hopital::factory()->GrandDakar()->create();

        Hopital::factory()->Principal()->create();
        Hopital::factory()->GrandYoff()->create();


    }
}
