<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\CreatesApplication;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuthController;
use App\Http\Requests\StoreMedecinRequest;
use App\Http\Requests\StorePatientRequest;

class AuthControllerUnitTest extends TestCase
{
    use CreatesApplication;

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
            'nom'=> 'heber tochi',
            'email' => 'hebert@example.com',
            'password' => Hash::make('password123'),
            'telephone'=>'779999911',
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            'is_blocked' => 0,
        ]);

        // Envoyez une demande de connexion avec les identifiants valides
        $request = new LoginRequest([
            'email' => 'hebert@example.com',
            'password' => 'password123',
        ]);
        $response = $this->authController->login($request);
        $this->assertArrayHasKey('access_token', $response->original);
        $this->assertArrayHasKey('token_type', $response->original);
        $this->assertArrayHasKey('expires_in', $response->original);
    }

    public function testLoginWithUserBlocked()
    {
        //Créez un utilisateur avec des identifiants valides
         User::factory()->create([
            'nom'=> 'heber tochi',
            'email' => 'albertine@example.com',
            'password' => Hash::make('password123'),
            'telephone'=>'779999912',
            'genre' => 'homme',
            'role_id' => 3,
            'ville_id' => 1,

            'is_blocked' => 1,
        ]);

        // Envoyez une demande de connexion avec les identifiants valides
        $request = new LoginRequest([
            'email' => 'albertine@example.com',
            'password' => 'password123',
        ]);
        $response = $this->authController->login($request);

        // Assurez-vous que la réponse contient un jeton d'accès
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assurez-vous que le code de statut HTTP est 401 (Unauthorized)
        $this->assertEquals(401, $response->getStatusCode());
    
        // Assurez-vous que la réponse contient le message approprié
        $expectedContent = [
            'error' => 'Votre compte est bloqué'
        ];
        $this->assertEquals($expectedContent, json_decode($response->getContent(), true));
    }


    public function testUnitRegisterMedecin()
    {
       

        // Créez une demande HTTP simulée avec des données valides
        $storeMedecinRequest = new StoreMedecinRequest();
        $storeMedecinRequest->merge([
            'nom' => 'medecin',
            'email' => 'medecine@example.com',
            'password' => 'password',
            'telephone' => '778523256',
            'genre' => 'homme',
            'ville_id' => 1,
            'hopital_id' => 1,
            'secteur_activite_id' => 1,
            'password_confirmation' => 'password',

        ]);
   
        // Déclenchez la validation et remplissez les données validées
        $storeMedecinRequest->setContainer($this->app)->validateResolved();
    
        $response = $this->authController->registerMedecin($storeMedecinRequest);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('votre demande été pris en compte', $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);

    }

    public function testUnitRegisterPatient()
    {
        $storePatientRequest = new StorePatientRequest();
        $storePatientRequest->merge([
            'nom' => 'patient',
            'email' => 'patient@example.com',
            'password' => 'password',
            'telephone' => '779999911',
            'genre' => 'homme',
            'ville_id' => 1,
            'password_confirmation' => 'password',

        ]);
   
        // Déclenchez la validation et remplissez les données validées
        $storePatientRequest->setContainer($this->app)->validateResolved();
    
        $response = $this->authController->registerPatient($storePatientRequest);
      
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());


        $responseData = $response->getData(true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('compte créé avec succès', $responseData['message']);
        $this->assertArrayHasKey('user', $responseData);

    }

    public function testUnitLogout()
    {
        // Créer un utilisateur fictif
        $user = User::factory()->create([
            'nom' => 'logout',
            'email' => 'logoutuser@example.com',
            'password' => 'password',
            'telephone' => '779999912',
            'genre' => 'homme',
            'ville_id' => 1,
            ]);
    
            $token = auth('api')->login($user);

            // Ajouter le jeton JWT à l'en-tête de la requête
            $this->withHeader('Authorization', 'Bearer '.$token);
            
            // Appeler la méthode de déconnexion
            $response = $this->authController->logout();
    
        // Vérifier que l'utilisateur est bien déconnecté
        $this->assertGuest('api');
    
        // Vérifier que la réponse est une instance de JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);
    
        // Vérifier que la réponse contient le message de déconnexion
        $responseData = $response->getData(true);
        $this->assertEquals('vous êtes déconnecté', $responseData['message']);
    }
    


}
