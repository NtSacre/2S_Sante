<?php

namespace Tests\Feature;

use App\Models\Temoignage;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemoignageTest extends TestCase
{
    use WithFaker;
    protected function authenticateUser($telephone)
    {
        // Créer un utilisateur de test
        $user = User::factory()->create([
            'nom'=> $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'),
            'telephone'=>$telephone,
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,
            'is_blocked' => 0,
        ]);



        $this->actingAs($user);

        return $user;
    }

    protected function createTemoignage($user)
    {
  
return [
    'temoignage' => $this->faker->sentence,
    'user_id' => $user->id,
    
   
  
];
        
    }


    public function testIndexTemoignage()
    {
        $user = $this->authenticateuser('7723333333');

        Temoignage::factory()->count(5)->create($this->createTemoignage($user));

        $response = $this->getJson('api/temoignage');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'temoignages' => [
                  '*'=> [
                    'id',
                    'temoignage',
                    'created_at',
                    'patient',
                    ]
                    
                ],
        ]);
    }

    public function testStoreTemoignage()
    {
        $user = $this->authenticateUser('783333311');



        $response = $this->postJson('api/temoignage', $this->createTemoignage($user));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'temoignage' => [
                    'id',
                    'temoignage',
                    'created_at',
                    'updated_at',
                    ]
                
            ]);

    }

    public function testShowTemoignage()
    {
        $user = $this->authenticateUser('773332233');

        $temoignage = Temoignage::factory()->create( $this->createTemoignage($user));

        $response = $this->getJson("api/temoignage/$temoignage->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'temoignage' => [
                    'id',
                    'temoignage',
     
                    'updated_at',

                    'created_at',
                    
                ],
            ]);
    }

    public function testUpdateTemoignage()
    {
        $user = $this->authenticateUser('772232323');

        $temoignage = Temoignage::factory()->create($this->createTemoignage($user));



        $response = $this->putJson("api/temoignage/$temoignage->id", $this->createTemoignage($user));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'temoignage' => [
                    'id',
                    'temoignage',
       
                    'updated_at',

                    'created_at',
                    
                ],
            ]);

    }

    public function testDestroyTemoignage()
    {
        $user = $this->authenticateUser('773222233');

        $temoignage = Temoignage::factory()->create($this->createTemoignage($user));

        $response = $this->deleteJson("api/temoignage/$temoignage->id");

        $response->assertStatus(200);
    }
}
