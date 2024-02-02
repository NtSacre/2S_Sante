<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleTest extends TestCase
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

    protected function createArticle($medecin)
    {
  
return [
    'titre' => $this->faker->sentence,
    'description' => $this->faker->paragraph,
    'image' => UploadedFile::fake()->image('article.jpg'),
    'user_id' => $medecin->id,
    'is_deleted' => false,
];
        
    }


    public function testIndex()
    {
        $medecin = $this->authenticateMedecin('778803200');

        Article::factory()->count(5)->create($this->createArticle($medecin));

        $response = $this->getJson(route('article.index'));

        $response->assertStatus(200)
        ->assertJsonStructure([
            'Articles' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'titre',
                        'description',
                        'image',
                        'user_id',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ]);
    }

    public function testStore()
    {
        $medecin = $this->authenticateMedecin('781590082');



        $response = $this->postJson(route('article.store'), $this->createArticle($medecin));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'article' => [
                    'id',
                    'titre',
                    'description',
                    'image',
                    'medecin',
                    'created_at',
                    
                ],
            ]);

        Storage::disk('public')->delete($response['article']['image']);
    }

    public function testShow()
    {
        $medecin = $this->authenticateMedecin('773579461');

        $article = Article::factory()->create( $this->createArticle($medecin));

        $response = $this->getJson(route('article.show', $article->id));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'article' => [
                    'id',
                    'titre',
                    'description',
                    'image',
                    'medecin',
                    'created_at',
                    
                ],
            ]);
    }

    public function testUpdate()
    {
        $medecin = $this->authenticateMedecin('771234569');

        $article = Article::factory()->create($this->createArticle($medecin));



        $response = $this->postJson(route('article.update', $article->id), $this->createArticle($medecin));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'article' => [
                    'id',
                    'titre',
                    'description',
                    'image',
                    'medecin',
                    'created_at',
                    
                ],
            ]);

        Storage::disk('public')->delete($response['article']['image']);
    }

    public function testDestroy()
    {
        $medecin = $this->authenticateMedecin('774569874');

        $article = Article::factory()->create($this->createArticle($medecin));

        $response = $this->deleteJson(route('article.destroy', $article->id));

        $response->assertStatus(200)
            ->assertJson([
                'message' =>  "l'article a été supprimer avec succès",
            ]);
    }
}
