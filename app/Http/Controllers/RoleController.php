<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;

class RoleController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            
            'Roles'=> Role::all(),
        ]);
    
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        
     try {
        $infoRoleValide=$request->validated();
        $role= Role::create($infoRoleValide);
        if($role){
            return response()->json([
               
                'message'=> 'Role enregistré avec succès',
                'role'=>$role
            ],201);
        }else{
            return response()->json([
                
                'message'=> 'Role non enregistré',
            ],500);
        }
     } catch (\Throwable $th) {
        return response()->json([
                
            'erreur'=> $th->getMessage(),
        ]);
     }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
       
       try {
        $role = Role::findOrFail($id);
        if($role->exists()){
            return response()->json([
               
                'role'=> $role,
            ], 200);
        }else{
            return response()->json([
                
                'message'=> 'role non trouvée',
            ], 500);
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
    public function update(UpdateRoleRequest $request, string $id)
    {

        try {
$role = Role::findOrFail($id);
            if($role){
                $infoRoleValide=$request->validated();
           
            if($role->update($infoRoleValide)){
                return response()->json([
                    
                    'message'=> 'Role modifié',
                    'role'=>$role
                ], 200);
            }else{
                return response()->json([
                  
                    'message'=> 'Role non modifié',
                ]);
            }
        }
        } catch (\Throwable $th) {
            return response()->json([
                  
                'erreur'=> $th->getMessage(),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);
           
           
                if($role->delete()){
                    
                        return response()->json([
                            
                            'message'=> 'Role supprimée',
                        ], 200);
                    }
            
        } catch (\Throwable $th) {
            return response()->json([
                
                'erreur'=> $th->getMessage(),
            ]);
        }
        
    }
}
