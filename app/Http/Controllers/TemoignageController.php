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
            ], 204);
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
    public function show(string $id)
    {
        try {
            $temoignage = Temoignage::findOrFail($id);
            return response()->json([
                
                'temoignage' => $temoignage,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                
                'erreur' => $th->getMessage(),
            ]);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemoignageRequest $request, string $id)
    {
        try {
           $temoignage = Temoignage::findOrFail($id);
           $donneTemoignageValider= $request->validated();
          $temoignage->update($donneTemoignageValider);
           return response()->json([
                'message' => 'temoignage modifié avec succès',
                'temoignage' => $temoignage,
        ], 200);
        } catch (\Throwable $th) {
            return response()->json([
            'erreur' => $th->getMessage(),
        ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $temoignage = Temoignage::findOrFail($id);
            $temoignage->is_deleted = true;
            return response()->json([
                'message' => 'témoignage supprimé avec succès',
            ],204);
        } catch (\Throwable $th) {
            return response()->json([
                'erreur' => $th->getMessage(),
            ]);
        }
    }
}
