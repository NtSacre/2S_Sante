<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use App\Http\Resources\VilleResource;
use App\Http\Requests\StoreVilleRequest;
use App\Http\Requests\UpdateVilleRequest;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ville = Ville::all();
        return response()->json([
            
            'villes'=> VilleResource::collection($ville),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVilleRequest $request)
    {
        try {
            $infoVilleValide=$request->validated();
            $ville= Ville::create($infoVilleValide);
            if($ville){
                return response()->json([
                   
                    'message'=> 'Vile enregistré avec succès',
                    'ville'=>new VilleResource($ville)
                ],201);
            }
         } catch (\Throwable $th) {
            return response()->json([
                    
                'erreur'=> $th->getMessage(),
            ],500);
         }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ville $ville)
    {
        try {
            
            if($ville){
                return response()->json([
                   
                    'ville'=> new VilleResource($ville),
                ], 200);
            }
           } catch (\Throwable $th) {
            return response()->json([
               
                'erreur'=> $th->getMessage(),
            ]);
           }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVilleRequest $request, Ville $ville)
    {
        try {
            
            if($ville){
                $infoVilleValide=$request->validated();
           
            if($ville->update($infoVilleValide)){
                return response()->json([
                    
                    'message'=> 'Ville modifié',
                    'ville'=>new VilleResource($ville)
                ], 200);
            }
        }
        } catch (\Throwable $th) {
            return response()->json([
                  
                'erreur'=> $th->getMessage(),
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ville $ville)
    {
        try {

            if($ville->delete()){
                
                    return response()->json([
                        
                        'message'=> 'Ville supprimé',
                    ], 200);
                }
        
    } catch (\Throwable $th) {
        return response()->json([
            
            'erreur'=> $th->getMessage(),
        ]);
    }
    }
}
