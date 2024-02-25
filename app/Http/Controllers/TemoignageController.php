<?php

namespace App\Http\Controllers;

use App\Models\Temoignage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTemoignageRequest;
use App\Http\Requests\UpdateTemoignageRequest;
use App\Http\Resources\TemoignageResource;

class TemoignageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $temoignages= Temoignage::where('user_id', Auth::user()->id)
        ->where('is_deleted',false)
        ->get();
      
        if($temoignages->all() == null){
            return response()->json([
                'message' => 'aucun témoignage pour l\'instant'
            ], 200);
        }
         
            return response()->json([
                'temoignages' =>  TemoignageResource::collection($temoignages)
            ], 200);
        
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemoignageRequest $request)
    {
        try {
            $this->authorize('create', Temoignage::class);
$temoignageExiste = Temoignage::where('user_id', auth()->user()->id)
->where('temoignage', $request->temoignage)
->first();
if($temoignageExiste && !$temoignageExiste->is_deleted){
    return response()->json([
        'erreur' => 'temoignage simulaire existe déjà',
      
    ], 409);
}
            $donneTemoignageValider=$request->validated();
            $donneTemoignageValider['user_id'] = auth()->user()->id;
            $temoignage=Temoignage::create( $donneTemoignageValider);
            $temoignage->save();
            return response()->json([
                'message' => 'temoignage bien enregistré',
                'temoignage' => $temoignage,
            ], 201);
            
        } catch (\Throwable $th) {
            return response()->json([
                'erreur' => $th->getMessage(),
            ]);
        }
  

    }

    /**
     * Display the specified resource.
     */
    public function show(Temoignage $temoignage)
    {
        try {
            $this->authorize('view', $temoignage);

           if(!$temoignage->is_deleted){
            return response()->json([
                
                'temoignage' => $temoignage,
            ], 200);
           }else{
            return response()->json([
                
                'erreur' => 'Ressource non trouvée',
            ], 404);
           }

        } catch (\Throwable $th) {
            return response()->json([
                
                'erreur' => $th->getMessage(),
            ], 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemoignageRequest $request, Temoignage $temoignage)
    {
        try {

            $this->authorize('update', $temoignage);

            if($temoignage->is_deleted){
                return response()->json([ "error" => 'Ressource non trouvée'
            ], 404);
            }
            

           $donneTemoignageValider= $request->validated();
          $temoignage->update($donneTemoignageValider);
           return response()->json([
                'message' => 'temoignage modifié avec succès',
                'temoignage' => $temoignage,
        ], 200);
        } catch (\Throwable $th) {
            return response()->json([
            'erreur' => $th->getMessage(),
        ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Temoignage $temoignage)
    {
        try {
            if(!$temoignage->is_deleted){
                $temoignage->is_deleted = true;
                $temoignage->update();
                return response()->json([
                    'message' => 'témoignage supprimé avec succès',
                ],200);
            }else{
                return response()->json([
                    'erreur' => 'Ressource non trouvée',
                ],404);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'erreur' => $th->getMessage(),
            ]);
        }
    }
}
