<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\InfoSupMedecin;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\UploadedFile;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Controllers\ArticleController;
use Illuminate\Foundation\Testing\WithFaker;

// class ArticleControllerUnitTest extends TestCase
// {
//     use WithFaker;
//     protected $articleController;

//     protected function setUp(): void
//     {
//         parent::setUp();
//         $this->articleController = new ArticleController();
//     }
//     /**
//      * A basic unit test example.
//      */
//     public function testUnitCreateArticle()
//     {
//         $user = User::factory()->create([
//             'nom' => 'un fichu medecin',
//             'email' => 'medecin@example.com',
//             'password' => 'password',
//             'telephone' => '779999952',
//             'genre' => 'homme',
//             'role_id' => 2,
//             'ville_id' => 1,
//             ]);
//             $user->infoSupMedecin()->create([
//                 'accepter' => 1,
//                 'user_id' => $user->id,
//                 'hopital_id' => 1,
//                 'secteur_activite_id' => 1,
//             ]);
    
//            $this->actingAs($user);

//             $storeArticleRequest = new StoreArticleRequest();
//         $storeArticleRequest->merge([

//             'titre' => $this->faker->sentence,
//             'description' => $this->faker->paragraph,
//             'image' => UploadedFile::fake()->image('article.jpg'),
//             'is_deleted' => false,

//         ]);
   
//         $storeArticleRequest->setContainer($this->app)->validateResolved();
           
//             $response = $this->articleController->store($storeArticleRequest);

           
//             $this->assertInstanceOf(JsonResponse::class, $response);

//             $this->assertEquals(201, $response->getStatusCode());
    
    
//             $responseData = $response->getData(true);
//             $this->assertArrayHasKey('message', $responseData);
//             $this->assertEquals('compte créé avec succès', $responseData['message']);
//             $this->assertArrayHasKey('user', $responseData);
//     }
// }
