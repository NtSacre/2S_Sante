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

    public function voirArticle(string $id){
        try {
           $article = Article::findOrFail($id);
          
            

            return response()->json([
                
                "article" => new ArticleResource($article),
    
            ], 200);
        


        } catch (\Throwable $th) {
            return response()->json([
                
                "erreur" => $th->getMessage(),
    
            ], 200);
        }
 
    }

    public function planningMedecin()
{
    try {
        $plannings = Planning::join('users', 'plannings.user_id', '=', 'users.id')
        ->select('plannings.*', 'users.nom as user_nom')
        ->where('plannings.is_deleted', false)
        ->get();

if($plannings->all() == null){
    return response()->json([
                   
        "message" => 'Aucun planning publié',
       

    ], 204);
}


    $groupedPlannings = $plannings->groupBy('user_nom');
    return response()->json(['plannings' => $groupedPlannings]);

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
        return response()->json(['message' => "aucun medecin trouvé"], 200);

    }

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
            ], 204);
        }
         
            return response()->json([
                'temoignages' => $temoignages
            ], 200);
        
    }

}
