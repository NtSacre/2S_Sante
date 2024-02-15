<?php

namespace Tests\Feature;

use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
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
            'role_id' => 1,
            'ville_id' => 1,
            'is_blocked' => 0,
        ]);



        $this->actingAs($user);

        return $user;
    }

    protected function createRole($user)
    {
  
return [
    'nom' => $this->faker->sentence,
    'description' => $this->faker->paragraph,
  
];
        
    }


    public function testIndexRole()
    {
        $user = $this->authenticateuser('772222221');

        Role::factory()->count(5)->create($this->createRole($user));

        $response = $this->getJson('api/role');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'Roles' => [
                  '*'=> [
                    'id',
                    'nom',
                    'description',
                    'created_at',
                    'updated_at',
                    ]
                    
                ],
        ]);
    }

    public function testStoreRole()
    {
        $user = $this->authenticateUser('782222211');



        $response = $this->postJson('api/role', $this->createRole($user));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message'
                
            ]);

    }

    public function testShowRole()
    {
        $user = $this->authenticateUser('772221211');

        $role = Role::factory()->create( $this->createRole($user));

        $response = $this->getJson("api/role/$role->id" );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'role' => [
                    'id',
                    'nom',
                    'description',
                    'updated_at',

                    'created_at',
                    
                ],
            ]);
    }

    public function testUpdateRole()
    {
        $user = $this->authenticateUser('772211111');

        $role = Role::factory()->create($this->createRole($user));



        $response = $this->putJson("api/role/$role->id", $this->createRole($user));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'role' => [
                    'id',
                    'nom',
                    'description',
                    'updated_at',

                    'created_at',
                    
                ],
            ]);

    }

    public function testDestroyRole()
    {
        $user = $this->authenticateUser('772111111');

        $role = Role::factory()->create($this->createRole($user));

        $response = $this->deleteJson("api/role/$role->id");

        $response->assertStatus(200);
    }
}
