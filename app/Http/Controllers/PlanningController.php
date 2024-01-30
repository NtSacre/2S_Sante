<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePlanningRequest;
use App\Http\Requests\UpdatePlanningRequest;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planning = Planning::where('user_id', Auth::user()->id)
        ->where('is_deleted', false)->paginate(5);
        if($planning->count() > 0){
            return response()->json([
                'plannings' => $planning
            ], 200);
        }else{
            return response()->json([
               
                'message' =>'Aucun planning enregistré',
            ], 204);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                    "message" => "Planning à été enregistrer avec succès",
                    
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Planning $planning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanningRequest $request, string $id)
    {
        try {
$planning = Planning::findOrFail($id);
            $donneePlanningValide = $request->validated();
        

    
            $donneePlanningValide['user_id']=Auth::user()->id;
            
    
            if ($planning->update($donneePlanningValide)) {
                return response()->json([
                    "message" => "Planning à été modifier avec succès",
                    
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
    public function destroy(string $id)
    {
       try {
        $planning = Planning::findOrFail($id);
        if($planning){
            $planning->is_deleted = true;
           
            if($planning->update()){
                return response()->json([
                   
                    "message" => "Planning a été supprimer avec succès"
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
