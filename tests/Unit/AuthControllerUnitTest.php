<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\AuthController;

class AuthControllerUnitTest extends TestCase
{
    protected $authController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authController = new AuthController();
    }

    public function testUnitLoginWithValidCredentials()
    {
        // Créez un utilisateur avec des identifiants valides
         User::factory()->create([
            'nom'=> 'roger',
            'email' => 'roger@example.com',
            'password' => bcrypt('password123'),
            'telephone'=>'779999999',
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            'is_blocked' => 1,
        ]);

        // Envoyez une demande de connexion avec les identifiants valides
        $request = new Request([
            'email' => 'roger@example.com',
            'password' => 'password123',
        ]);
        $response = $this->authController->login($request);

        // Assurez-vous que la réponse contient un jeton d'accès
        $this->assertArrayHasKey('access_token', $response->original);
        $this->assertArrayHasKey('token_type', $response->original);
        $this->assertArrayHasKey('expires_in', $response->original);
    }
}
