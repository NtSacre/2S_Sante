<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ville;
use App\Models\Article;
use App\Models\Hopital;
use App\Models\Planning;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use App\Models\SecteurActivite;
use App\Http\Resources\VilleResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\HopitalResource;
use App\Http\Resources\MedecinResource;
use App\Http\Resources\SecteurActiviteResource;

class HomeController extends Controller
{
    public function article(){
        $articles = Article::where('is_deleted',false)->with('medecin')->get();
        $villes = Ville::all();
        $secteur_actives = SecteurActivite::all();
        $hopitals = Hopital::all();


       if($articles->all() !== null) {
           
        return response()->json([
                
            "articles" =>   ArticleResource::collection($articles),
            "secteur_actives" =>  SecteurActiviteResource::collection($secteur_actives),
            "hopitals" => HopitalResource::collection($hopitals),
            "villes" => VilleResource::collection($villes)



        ], 200);

       }else{
        return response()->json([
                   
            "message" => 'Aucun article publié',
           

        ], 204);
       }
        
    }

    public function voirArticle(Article $article){
        try {
          
          
            if($article->is_deleted){
                return response()->json([
            
                    "error" => "Ressource non trouvée",
                   
                ], 404);
            }else{
                return response()->json([
                
                    "article" => new ArticleResource($article),
        
                ], 200);
            }


        


        } catch (\Throwable $th) {
            return response()->json([
                
                "erreur" => $th->getMessage(),
    
            ], 500);
        }
 
    }

    public function planningMedecin(User $medecin)
    {
        try {

            if($medecin->role->nom != 'medecin'){
                return response()->json([
    
                    "erreur" => 'Utilisateur non trouvée',
            
            
                ], 200);
            }
            $plannings = Planning::where('is_deleted', 0)
            ->where('user_id', $medecin->id)
            ->get();
    
    if($plannings->all() == null){
        return response()->json([
    
            "message" => 'Aucun planning publié',
    
    
        ], 200);
    }
    
    
    
        return response()->json(['plannings' => $plannings]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'erreur' => $th->getMessage()
            ]);
    
        }
    
    
    }

public function detailMedecin()
{
    $medecin = User::where('role_id', 2)
    ->where('is_blocked', false)
    ->whereHas('infoSupMedecin', function ($query) {
        $query->where('accepter', 1);
    })
    ->with('infoSupMedecin')
    ->get();

    if($medecin->all() !== null){
        
        return response()->json(['medecins' => MedecinResource::collection($medecin)], 200);
    }else{
        return response()->json(['erreur' => "aucun medecin trouvé"], 200);

    }

}
public function DetailPlanning(Planning $planning)
{
 
  
    if($planning->is_deleted != false){
        return response()->json([
            'message' => 'planning non trouvé',
        ], 200);
    }
     
        return response()->json([
            'planning' => $planning
        ], 200);
    
}
        /**
     * Display a listing of the resource.
     */
    public function toutTemoignage()
    {
        $temoignages= Temoignage::where('is_deleted',false)->get();
      
        if($temoignages->all() == null){
            return response()->json([
                'message' => 'aucun témoignage pour l\'instant'
            ], 200);
        }
         
            return response()->json([
                'temoignages' => $temoignages
            ], 200);
        
    }

}
