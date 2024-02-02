<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\InfoSupMedecin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ValidationCompteMedecin;
use App\Http\Resources\MedecinResource;
use App\Http\Requests\StoreMedecinRequest;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdateMedecinRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Foundation\Auth\User as AuthUser;

class AuthController extends Controller
{
       /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registerMedecin' , 'registerPatient']]);
    }


    

 
    


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $user = User::where('email',request('email'))->first();
        if($user == null){
            return response()->json([
             'message' => 'Utilisateur non trouvé'
            ],404);
        }
        if($user->role->nom == 'medecin'){

        if($user->infoSupMedecin->accepter === 1 && $user->is_blocked !== 0){
            return response()->json(['error' => 'Votre compte est bloqué'], 401);
        }elseif ($user->infoSupMedecin->accepter === 0 && $user->is_blocked === 0){
            return response()->json(['error' => 'validation compte en cours'], 401);

        }else{

            $credentials = request(['email', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'non authorizé'], 401);
            }
    
            return $this->respondWithToken([
                'user' => Auth::user(),
                'token'=>$token
            ]);

        }

    }elseif($user->is_blocked !== 0){
        return response()->json(['error' => 'Votre compte est bloqué'], 401);

    }
    $credentials = request(['email', 'password']);

    if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'non authorizé'], 401);
    }

    return $this->respondWithToken([
        'user' => Auth::user(),
        'token'=>$token
    ]);
}
  
public function registerMedecin(StoreMedecinRequest $request){
        try {
            
        $donneeMedecinValider=$request->validated();
      
        if ($request->file('image') !== null && !$request->file('image')->getError()) {
            $donneeMedecinValider['image'] = $request->file('image')->store('image', 'public');

            $donneeMedecinValider['password']=Hash::make($donneeMedecinValider['password']);
            $donneeMedecinValider['role_id']=2;
    
            
            $medecin = User::create([
                'nom' => $donneeMedecinValider['nom'],
                'email' => $donneeMedecinValider['email'],
                'password' => $donneeMedecinValider['password'],
                'telephone' => $donneeMedecinValider['telephone'],
                'genre' => $donneeMedecinValider['genre'],
                'image' => $donneeMedecinValider['image'],
                'role_id' => $donneeMedecinValider['role_id'],
                'ville_id' => $donneeMedecinValider['ville_id'],

            ]);
             $infoSupMedecin=InfoSupMedecin::create([
                'hopital_id' => $donneeMedecinValider['hopital_id'],
              'secteur_activite_id' => $donneeMedecinValider['secteur_activite_id'],
              'user_id' => $medecin->id,
    
            ]);
            if($medecin && $infoSupMedecin){
                return response()->json([
                 'message' => 'votre demande été pris en compte',
                    'user' => $medecin,
                    'info_sup_medecin' => $infoSupMedecin,
                ], 201);
            }

        }
        $donneeMedecinValider['password']=Hash::make($donneeMedecinValider['password']);
        $donneeMedecinValider['role_id']=2;
        $medecin = User::create([
            'nom' => $donneeMedecinValider['nom'],
            'email' => $donneeMedecinValider['email'],
            'password' => $donneeMedecinValider['password'],
            'telephone' => $donneeMedecinValider['telephone'],
            'genre' => $donneeMedecinValider['genre'],
            'role_id' => $donneeMedecinValider['role_id'],
            'ville_id' => $donneeMedecinValider['ville_id'],

        ]);
         $infoSupMedecin=InfoSupMedecin::create([
            'hopital_id' => $donneeMedecinValider['hopital_id'],
          'secteur_activite_id' => $donneeMedecinValider['secteur_activite_id'],
          'user_id' => $medecin->id,

        ]);
        if($medecin && $infoSupMedecin){
            return response()->json([
             'message' => 'votre demande été pris en compte',
                'user' => new MedecinResource($medecin),
            ], 201);
        }
    } catch (\Throwable $th) {

      return response()->json([
        'erreur' => $th->getMessage(),
       ], 500);

    }

    }

    public function registerPatient(StorePatientRequest $request){

        
        try {
            $donneePatientValider=$request->validated();
            $donneePatientValider['role_id']=3;

            $patient = User::create($donneePatientValider);
            if($patient){
                return response()->json([
                'message' => 'compte créé avec sucess',
                    'user' => $patient,
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                ], 500);
        }

    }


    public function modificationPatient(UpdatePatientRequest $request, string $id){

        
        try {
            $patient = User::where('id', $id)->first();
            $donneePatientValider=$request->validated();
            $donneePatientValider['role_id']=3;

            
            if($patient->update($donneePatientValider)){
                return response()->json([
                'message' => 'compte modifié avec sucess',
                    'user' => $patient,
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                ], 500);
        }

    }

    public function modificationMedecin(UpdateMedecinRequest $request, string $id){
        try {
            
        $donneeMedecinValider=$request->validated();
      
        if ($request->file('image') !== null && !$request->file('image')->getError()) {
            $donneeMedecinValider['image'] = $request->file('image')->store('image', 'public');

            $donneeMedecinValider['role_id']=2;
    $medecin = User::where('id', $id)->first();
    $infoSupMedecin =InfoSupMedecin::where('user_id',$id)->first();
            $medecin->updated([
                'nom' => $donneeMedecinValider['nom'],
                'email' => $donneeMedecinValider['email'],
                'telephone' => $donneeMedecinValider['telephone'],
                'genre' => $donneeMedecinValider['genre'],
                'image' => $donneeMedecinValider['image'],
                'role_id' => $donneeMedecinValider['role_id'],
                'ville_id' => $donneeMedecinValider['ville_id'],

            ]);
            dd($medecin);
             $infoSupMedecin=$infoSupMedecin->updated([
                'hopital_id' => $donneeMedecinValider['hopital_id'],
              'secteur_activite_id' => $donneeMedecinValider['secteur_activite_id'],
              'user_id' => $medecin->id,
    
            ]);
            if($medecin && $infoSupMedecin){
                return response()->json([
                 'message' => 'compte modifié',
                    'user' => $medecin,
                    'info_sup_medecin' => $infoSupMedecin,
                ], 201);
            }

        }
        $medecin = User::where('id', $id)->first();
    $infoSupMedecin =InfoSupMedecin::where('user_id',$id)->first();

        $donneeMedecinValider['role_id']=2;
        $medecin->updated([
            'nom' => $donneeMedecinValider['nom'],
            'email' => $donneeMedecinValider['email'],
            'telephone' => $donneeMedecinValider['telephone'],
            'genre' => $donneeMedecinValider['genre'],
            'role_id' => $donneeMedecinValider['role_id'],
            'ville_id' => $donneeMedecinValider['ville_id'],

        ]);
     
         $infoSupMedecin=$infoSupMedecin->updated([
            'hopital_id' => $donneeMedecinValider['hopital_id'],
          'secteur_activite_id' => $donneeMedecinValider['secteur_activite_id'],
          'user_id' => $medecin->id,

        ]);
        if($medecin && $infoSupMedecin){
            return response()->json([
             'message' => 'Compte modifié avec succès',
                'user' => new MedecinResource($medecin),
            ], 201);
        }
    } catch (\Throwable $th) {

      return response()->json([
        'erreur' => $th->getMessage(),
       ], 500);

    }

    }






    /**
    
     * block  patient .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bloquerUser(string $id){

        try {
            $user = User::findOrFail($id);
            $user->is_blocked = true;
            if($user->update()){
             return response()->json([
                 'message' => 'utilisateur bloqué avec succès'
             ],200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'erreur' => $th->getMessage()
            ]);
        }
    
     
     
        }

   /**
     * block  medecin .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function debloquerUser(string $id){

        try {
            $user = User::findOrFail($id);

            if($user->update()){

             return response()->json([
                 'message' => 'utilisateur débloqué avec succès'
             ],200);

            }

        } catch (\Throwable $th) {
           return response()->json([
                 'erreur' => $th->getMessage()
             ]);
        }

     
        }

        public function accepterMedecin(string $id){

            try {
                $infomedecin = InfoSupMedecin::where('user_id',$id)->first();
               
                if($infomedecin){
                    $infomedecin->accepter = true;

                    if($infomedecin->save()){
                        $medecin = User::where('id',$id)->first();
                        Mail::to($medecin->email)
                        ->send(new ValidationCompteMedecin($medecin));

                        return response()->json([
                            'message' => 'Vous avez accepter la demande de Dr. '.$medecin->nom,
                            
                        ], 200);
                    }

                    
                }

            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                    
                ], 500);
            }
        }
    

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'vous êtes déconnecté']);
    }


    

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
   
     public function refresh()
     {
         return response()->json([
             'user' => Auth::user(),
             'authorisation' => [
                 'token' => Auth::refresh(),
                 'type' => 'bearer',
             ]
         ]);
     }
 

 /**
  * Get the token array structure.
  *
  * @param  string $token
  *
  * @return \Illuminate\Http\JsonResponse
  */
 protected function respondWithToken($token)
 {
     return response()->json([
         'access_token' => $token,
         'token_type' => 'bearer',
         'expires_in' => Auth::factory()->getTTL() * 60
     ]);
 }
}
