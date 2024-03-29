<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $article = Article::where('user_id', Auth::user()->id)
        ->where('is_deleted', false)->get();

        if($article->all() == null){
            return response()->json([
                'message' => 'aucun article pour l\'instant'
            ], 200);
        }

        return response()->json([
            'Articles' => $article
        ], 200);

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        try {
             $this->authorize('create', Article::class);


            $donneeArticleValide = $request->validated();
            $image = $request->file('image');

            if ($image !== null && !$image->getError()) {
                $donneeArticleValide['image'] = $image->store('image', 'public');
            }
            $donneeArticleValide['user_id'] = Auth::user()->id;


            $article = new Article($donneeArticleValide);

            if ($article->save()) {
                return response()->json([
                    "message" => "l'article à été enregistrer avec succès",

                    "article" => new ArticleResource($article),
                ], 201);
            } else {
                return response()->json([

                    "message" => "l'article n'a pas été enregistrer"
                ], 500);
            }
        } catch (\Throwable $th) {
             return response()->json([

                "erreur" => $th->getMessage(),
             ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try {
            $this->authorize('view', $article);
           if(!$article->is_deleted){
            return response()->json([

                "article" => new ArticleResource($article)
            ], 200);
           }else{
            return response()->json(["error" => "Ressource non trouvée" ], 404);
           }
        } catch (\Throwable $th) {
            return response()->json([
                "erreur" => $th->getMessage(),
            ], 500);

        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article )
    {

        try {


            $this->authorize('update', $article);
            $donneeArticleValide = $request->validated();
if($article->is_deleted){
    return response()->json([

        "error" => "Ressource non trouvée",
       
    ], 404);
}
            $donneeArticleValide['user_id'] = Auth::user()->id;

$image = $request->file('image') ? $request->file('image') : null;



            if ($image !== null && !$image->getError()) {
                if( $article->$image){
                    Storage::disk('public')->delete($article->image);

                }

                $donneeArticleValide['image'] = $image->store('image', 'public');
            }

            if ($article->update($donneeArticleValide)) {
                return response()->json([

                    "message" => "l'article à été modifié avec succès",
                    "article" => new ArticleResource($article),
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([

                "erreur" => $th->getMessage(),
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            $this->authorize('update', $article);

            if (!$article->is_deleted) {
                $article->is_deleted = true;

                if ($article->update()) {
                    return response()->json([

                        "message" => "l'article a été supprimer avec succès"
                    ], 200);
                }
            }else{
                return response()->json([
            
                    "error" => "Ressource non trouvée",
                   
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([

                "erreur" => $th->getMessage(),
            ], 500);
        }

    }
}
