<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PlanningResource;
use App\Http\Requests\StorePlanningRequest;
use App\Http\Requests\UpdatePlanningRequest;
use Tests\Feature\PlanningTest;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planning = Planning::where('user_id', Auth::user()->id)
        ->where('is_deleted', false)->get();

        if($planning->all() == null){
            return response()->json([
                'message' => 'aucun planning pour l\'instant'
            ], 200);
        }
            return response()->json([
                'plannings' => $planning
            ], 200);
        
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanningRequest $request)
    {
        try {
            $donneePlanningValide = $request->validated();
        
            // Recherche de plannings similaires
            $existingPlanning = Planning::where('user_id', Auth::user()->id)
                ->where('jour', $donneePlanningValide['jour'])
                ->first();
        
            if ($existingPlanning && $existingPlanning->is_deleted == false) {
                // Si un planning similaire existe déjà, retourner un message d'erreur
                return response()->json(['error' => 'Le jour pour ce planning existe déjà. Vous pouvez simplement modifier les créneaux existants.'], 409);
            }
        
            // Création d'un nouveau planning
            $planning = new Planning();
            $planning->user_id = Auth::user()->id;
            $planning->jour = $donneePlanningValide['jour'];
            $planning->creneaux = json_encode($donneePlanningValide['creneaux']);
        
            if ($planning->save()) {
                return response()->json([
                    "message" => "Le planning a été enregistré avec succès",
                    "planning" => $planning
                ], 201);
            } else {
                return response()->json([
                    "message" => "Le planning n'a pas été enregistré"
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "messageErreur" => $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Planning $planning)
    {
        try {






            if($planning->is_deleted == false) {
             return response()->json([
 
                 "planning" => new PlanningResource($planning)
             ], 200);
            }else{
                return response()->json([
 
                    "error" => 'planning non trouvé'
                ], 404);
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
    public function update(UpdatePlanningRequest $request, Planning $planning)
    {
        try {
            $donneePlanningValide = $request->validated();
        

         
       if ($planning->jour !== $donneePlanningValide['jour'] ) {
           return response()->json(['error' => 'vous ne pouvez pas modifier le jour'], 409);
       }
            
            // $planning->creneaux=$request->creneaux;
            
    
            if ($planning->update($donneePlanningValide)) {
                return response()->json([
                    "message" => "Planning a été modifié avec succès",
                    
                    "planning" => $planning
                ], 200);
            } else {
                return response()->json([
                  
                    "message" => "Planning n'a pas été modifié"
                ], 500);
            }
           } catch (\Throwable $th) {
            return response()->json([
                
                "messageErreur" => $th->getMessage(),
            ]);
           }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planning $planning)
    {
       try {
        if($planning->is_deleted === false) {
            $planning->is_deleted = true;
           
            if($planning->update()){
                return response()->json([
                   
                    "message" => "Planning a été supprimé avec succès"
                ], 200);
            }
        }else{
            return response()->json([
                   
                "error" => "Planning non trouvé"
            ], 404);
        }
       } catch (\Throwable $th) {
        return response()->json([
            "erreur" => $th->getMessage()
        ]);
       }
    }
}
