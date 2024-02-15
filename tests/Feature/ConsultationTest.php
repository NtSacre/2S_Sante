<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Planning;
use App\Models\Consultation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcceptationConsultation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConsultationTest extends TestCase
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

    "date"=> "2024-01-02",
	"heure_debut"=> "14:30:00",
	"heure_fin"=> "17:30:00",
    'user_id' => $medecin->id,
    'is_deleted' => false,
];
        
    }




    public function testIndexConsultation()
{
    $medecin = $this->authenticateMedecin('760000001');
    Planning::factory()->count(5)->create($this->createPlanning($medecin));

    $response = $this->getJson(route('consultation.index'));

    $response->assertStatus(200);
}

    public function testAccepterConsultation()
    {
        $consultation = Consultation::factory()->create([
            "planning_id"=>2,
            "heure"=> "13:20:00",
            "motif" => "Consultation_generale",
            "type"=>'en_ligne',
            "user_id" => 2,

        ]);
        $medecin = $this->authenticateMedecin('760000011');
       $planning= Planning::factory()->create($this->createPlanning($medecin));
        $consultation->planning()->associate($planning);
        $consultation->save();
        

        $response = $this->getJson(route('consultation.accepterConsultation', ['consultation' => $consultation->id]));

        $response->assertStatus(200);
    }

    public function testStoreConsultation()
    {
            $user = User::factory()->create([
        'nom' => 'Momodou Sail',
        'email' => $this->faker->unique()->safeEmail,
        'password' => bcrypt('password123'),

        'telephone' => '760000111',
        'genre' => 'homme',
        'role_id' => 3,
        'ville_id' => 1,

        
    ]);
    $this->actingAs($user);

        $data = [
            "planning_id"=>3,
            "heure"=> "13:20",
            "motif" => "Consultation_generale",
            "type"=>'en_ligne',

        ];

        $response = $this->postJson(route('consultation.store'), $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'consultation'=>[
                    "planning_id",
                    "heure",
                    "motif",
                    "user_id",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            ]);
    }

    public function testContacterPatient()
    {
    $this->authenticateMedecin('760001111');

        $patient = User::factory()->create([
            'nom' => 'Momodou Sail',
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'),
            'telephone' => '778324565',
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            
        ]);

        $response = $this->getJson(route('contacterPatient', ['patient' => $patient->id]));

        $response->assertRedirect();
    }
}
