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
                'message' => 'aucun article pour l\'instant'
            ], 204);
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
        

    
            $donneePlanningValide['user_id']=Auth::user()->id;
            
            $planning= new Planning($donneePlanningValide);
    
            if ($planning->save()) {
                return response()->json([
                    "message" => "Planning a été enregistré avec succès",
                    
                    "planning" => $planning
                ], 201);
            } else {
                return response()->json([
                  
                    "message" => "Planning n'a pas été enregistré"
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
            if($planning){
             return response()->json([
 
                 "planning" => new PlanningResource($planning)
             ], 200);
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
        

    
            $donneePlanningValide['user_id']=Auth::user()->id;
            
    
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
        if($planning){
            $planning->is_deleted = true;
           
            if($planning->update()){
                return response()->json([
                   
                    "message" => "Planning a été supprimé avec succès"
                ], 200);
            }
        }
       } catch (\Throwable $th) {
        return response()->json([
            "erreur" => $th->getMessage()
        ]);
       }
    }
}
