<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Planning;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanningTest extends TestCase
{

    use WithFaker;
    protected function authenticateMedecin($telephone)
    {
        // Créer un utilisateur de test
        $medecin = User::factory()->create([
            'nom'=> $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'),
            'telephone'=>$telephone,
            'genre' => 'homme',
            'role_id' => 2,
            'ville_id' => 1,

            'is_blocked' => 0,
        ]);

        $medecin->infoSupMedecin()->create([
            'accepter' => 1,
            'user_id' => $medecin->id,
            'hopital_id' => 1,
            'secteur_activite_id' => 1,
        ]);

        $this->actingAs($medecin);

        return $medecin;
    }

    protected function createPlanning($medecin)
    {
        return [
            "jour" => "lundi",
            "creneaux" => json_encode([
                [
                    "heure_debut" => "08:01",
                    "heure_fin" => "09:00"
                ]
            ]),
            'user_id' => $medecin->id,
            'is_deleted' => false,
        ];
    }

    protected function Planning($medecin)
    {
        return [
            "jour" => "lundi",
            "creneaux" => [
                [
                    "heure_debut" => "08:01",
                    "heure_fin" => "09:00"
                ]
            ],
            'user_id' => $medecin->id,
            'is_deleted' => false,
        ];
    }


    public function testIndexPlanning()
    {
        $medecin = $this->authenticateMedecin('780000001');

        Planning::factory()->count(5)->create($this->createPlanning($medecin));

        $response = $this->getJson(route('planning.index'));
dd($response);
        $response->assertStatus(200)
        ->assertJsonStructure([
           
            'plannings' => [
                '*' => [
                    "id",
                    "jour",
                    "status",
                    "creneaux" => [
                        '*' => [
                            "heure_debut",
                            "heure_fin"
                        ]
                    ],
                   
                    "created_at",
                   
                ]
            ]
        ]);
    }

    public function testStorePlanning()
    {
        $medecin = $this->authenticateMedecin('780000011');



        $response = $this->postJson(route('planning.store'), $this->Planning($medecin));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'planning' => [
                    "id",
                    "jour",
                    "creneaux" => [
                        '*' => [
                            "heure_debut",
                            "heure_fin"
                        ]
                    ],
                    
                  
                    "created_at",
                   
                ],
            ]);

        
    }

    public function testShowPlanning()
    {
        $medecin = $this->authenticateMedecin('780000111');

        $planning = Planning::factory()->create($this->createPlanning($medecin));

        $response = $this->getJson(route('planning.show', $planning->id));

        $response->assertStatus(200)
        ->assertJsonStructure([
            
            'planning' => [
                "id",
                "jour",
                "creneaux" => [
                    '*' => [
                        "heure_debut",
                        "heure_fin"
                    ]
                ],
                "medecin",
                "created_at",
            ],
        ]);
    }

    public function testUpdatePlanning()
    {
        $medecin = $this->authenticateMedecin('780001111');

        $planning = Planning::factory()->create($this->createPlanning($medecin));



        $response = $this->postJson(route('planning.update', $planning->id), $this->Planning($medecin));

        $response->assertStatus(200)
            
                ->assertJsonStructure([
                    'message',
                    'planning' => [
                        "id",
                        "jour",
                        "status",
                        "creneaux" => [
                            '*' => [
                                "heure_debut",
                                "heure_fin"
                            ]
                        ],
                        "is_deleted",
                        "user_id",
                        "created_at",
                        "updated_at"
                    ],
                ]);

    }

    public function testDestroyPlanning()
    {
        $medecin = $this->authenticateMedecin('780011111');

        $planning = Planning::factory()->create($this->createPlanning($medecin));

        $response = $this->deleteJson(route('planning.destroy', $planning->id));

        $response->assertStatus(200)
            ->assertJson([
                'message' =>  "Planning a été supprimé avec succès",
            ]);
    }

}

