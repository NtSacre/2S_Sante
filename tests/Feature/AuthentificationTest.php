<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthentificationTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */


    public function testRegisterPatientUnsuccessfully()
    {
        // Générer des données valides pour l'inscription d'un patient
        $data = [
            'nom' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' =>'password123',
            'telephone' => $this->faker->phoneNumber,
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            
        ];

        $response = $this->postJson('/api/register-patient', $data);

        // Vérifier que la réponse est correcte avec le code HTTP 422
        $response->assertStatus(422);


    }


    public function testRegisterPatientSuccessfully()
    {
        // Générer des données valides pour l'inscription d'un patient
        $data = [
            'nom' => 'Momodou Sail',
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' =>'password123',
            'telephone' => '770000001',
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            
        ];

        $response = $this->postJson('/api/register-patient', $data);

        // Vérifier que la réponse est correcte avec le code HTTP 201
        $response->assertStatus(201);

        // Vérifier que l'utilisateur a été correctement enregistré dans la base de données
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'role_id' => 3,
        ]);
    }

    public function testRegisterMedecinSuccessfully()
    {
        Storage::fake('public');

        $data = [
            'nom' => 'hermann du Con',
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' =>'password123',
            'telephone' => '770000011',
            'genre' => 'homme',
            'image' => UploadedFile::fake()->image('article.jpg'),
            'role_id' => 2,
            'ville_id' => 1,
            'hopital_id' => 1,
            'secteur_activite_id' => 1,
           
        ];

        $response = $this->postJson('/api/register-medecin', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'role_id' => 2,
        ]);

        $this->assertDatabaseHas('info_sup_medecins', [
            'user_id' => $response['user']['id'],

        ]);
    }

    public function testRegisterMedecinUnsuccessfully()
    {
        Storage::fake('public');

        $data = [
            'nom' => 'yo gou',
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' =>'password123',
            'telephone' => $this->faker->phoneNumber,
            'genre' => 'homme',
            'image' => UploadedFile::fake()->image('article.jpg'),
            'role_id' => 2,
            'ville_id' => 1,
            'hopital_id' => 1,
            'secteur_activite_id' => 1,
           
        ];

        $response = $this->postJson('/api/register-medecin', $data);

        $response->assertStatus(422);


    }

    public function testLoginWithValidCredentials()
    {
        // Créer un utilisateur de test
        $user = User::factory()->create([
            'nom'=> $this->faker->name,
            'email' => 'text@example.com',
            'password' => bcrypt('password123'),
            'telephone'=>'770000111',
            'genre' => 'homme',
            'role_id' => 2,
            'ville_id' => 1,

            'is_blocked' => 0,
        ]);

        $user->infoSupMedecin()->create([
            'accepter' => 1,
            'user_id' => $user->id,
            'hopital_id' => 1,
            'secteur_activite_id' => 1,
        ]);
    

        // Envoyer une requête de connexion avec des informations d'identification valides
        $response = $this->postJson('/api/login', [
            'email' => 'text@example.com',
            'password' => 'password123',
        ]);

        // Vérifier que la réponse est correcte
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);

        // Vérifier que l'utilisateur est correctement authentifié
        $this->assertAuthenticated();
    }
    public function testLoginWithValidInCredentials()
    {
        // Créer un utilisateur de test
        $user = User::factory()->create([
            'nom'=> $this->faker->name,
            'email' => 'tagee@example.com',
            'password' => bcrypt('password123'),
            'telephone'=>'770001111',
            'genre' => 'homme',
            'role_id' => 2,
            'ville_id' => 1,

            'is_blocked' => 1,
        ]);

        $user->infoSupMedecin()->create([
            'accepter' => 1,
            'user_id' => $user->id,
            'hopital_id' => 1,
            'secteur_activite_id' => 1,
        ]);
    

        // Envoyer une requête de connexion avec des informations d'identification valides
        $response = $this->postJson('/api/login', [
            'email' => 'tagee@example.com',
            'password' => 'password123',
        ]);

        // Vérifier que la réponse est correcte
        $response->assertStatus(401);


    }

    public function testLoginUsers()
    {
        // Créer un utilisateur de test
         User::factory()->create([
            'nom'=> $this->faker->name,
            'email' => 'tagu@example.com',
            'password' => bcrypt('password123'),
            'telephone'=>'770011111',
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            'is_blocked' => 0,
        ]);


    

        // Envoyer une requête de connexion avec des informations d'identification valides
        $response = $this->postJson('/api/login', [
            'email' => 'tagu@example.com',
            'password' => 'password123',
        ]);

        // Vérifier que la réponse est correcte
        $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);

        $this->assertAuthenticated();

    }

    public function testUserLogoutSuccessfully()
{
    // Créer un utilisateur de test
    $user = User::factory()->create([
        'nom'=> $this->faker->name,
        'email' => 'tuty@example.com',
        'password' => bcrypt('password123'),
        'telephone'=>'770111111',
        'genre' => 'homme',
        'role_id' => 3,
        'ville_id' => 1,
        'is_blocked' => 0,
    ]);

    $token = JWTAuth::fromUser($user);
    $response = $this->withHeader('Authorization', 'Bearer' . $token)
                     ->post('/api/logout');
    $response->assertStatus(200);


    // Vérifier que l'utilisateur n'est pas authentifié après la déconnexion
    $this->assertGuest();
}

public function testBloquerUserSuccessfully()
{
    // Créer un administrateur de test
    $admin = User::factory()->create([
        'nom'=> $this->faker->name,
        'email' => 'abdxw@example.com',
        'password' => bcrypt('password123'),
        'telephone'=>'771111111',
        'genre' => 'homme',
        'role_id' => 1,
        'ville_id' => 1,
        'is_blocked' => 0,
    ]);

    // Connecter l'administrateur
    

    $token = JWTAuth::fromUser($admin);
    $this->withHeader('Authorization', 'Bearer' . $token)
                     ->post('/api/login');

    // Créer un utilisateur de test
    $user = User::factory()->create([
        'nom'=> $this->faker->name,
        'email' => 'jea@example.com',
        'password' => bcrypt('password123'),
        'telephone'=>'771111112',
        'genre' => 'homme',
        'role_id' => 3,
        'ville_id' => 1,
        'is_blocked' => 0,
    ]);

    // Envoyer une requête pour bloquer l'utilisateur
    $response = $this->postJson('/api/bloquer-user/' . $user->id);

    // Vérifier que la réponse est correcte avec le message attendu
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'utilisateur bloqué avec succès',
        ]);

    // Relire l'utilisateur depuis la base de données pour s'assurer qu'il est réellement bloqué
    $user = User::findOrFail($user->id);
   
    $this->assertEquals(1, $user->is_blocked);
}

}
