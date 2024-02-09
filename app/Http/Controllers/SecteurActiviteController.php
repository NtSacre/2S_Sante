<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSecteurActiviteRequest;
use App\Http\Requests\UpdateSecteurActiviteRequest;
use App\Http\Resources\SecteurActiviteResource;
use App\Models\SecteurActivite;

class SecteurActiviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secteurActivite = SecteurActivite::all();
        return response()->json([
            
            'secteurActivites'=> SecteurActiviteResource::collection($secteurActivite),
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSecteurActiviteRequest $request)
    {
        try {
            $infoSecteurActiviteValide=$request->validated();
            $secteurActivite= SecteurActivite::create($infoSecteurActiviteValide);
            if($secteurActivite){
                return response()->json([
                   
                    'message'=> 'secteur activité enregistré avec succès',
                    'secteurActivite'=>new SecteurActiviteResource($secteurActivite)
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
    public function show(SecteurActivite $secteurActivite)
    {
        try {
            
            if($secteurActivite){
                return response()->json([
                   
                    'secteurActivite'=> new SecteurActiviteResource($secteurActivite),
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
    public function update(UpdateSecteurActiviteRequest $request, SecteurActivite $secteurActivite)
    {
        try {
            
                        if($secteurActivite){
                            $infoSecteurActiviteValide=$request->validated();
                       
                        if($secteurActivite->update($infoSecteurActiviteValide)){
                            return response()->json([
                                
                                'message'=> 'Secteur activité modifié',
                                'secteurActivite'=>new SecteurActiviteResource($secteurActivite)
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
    public function destroy(SecteurActivite $secteurActivite)
    {
        try {
           
           
           
                if($secteurActivite->delete()){
                    
                        return response()->json([
                            
                            'message'=> 'Secteur activité supprimé',
                        ], 200);
                    }
            
        } catch (\Throwable $th) {
            return response()->json([
                
                'erreur'=> $th->getMessage(),
            ]);
        }
    }
}
