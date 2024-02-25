<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\InfoSupMedecin;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ValidationCompteMedecin;
use App\Http\Resources\MedecinResource;
use App\Http\Resources\PatientResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreMedecinRequest;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdateMedecinRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function login(LoginRequest $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user == null ){
            return response()->json([
             'message' => 'Utilisateur non trouvé'
            ],404);
        }elseif($user->role == null){
            return response()->json([
                'message' => 'Utilisateur n\'a pas de role'
               ],500);
        }

        if($user->role->nom == 'medecin'){

        if($user->infoSupMedecin->accepter === 1 && $user->is_blocked !== 0){
            return response()->json(['message' => 'Votre compte est bloqué'], 404);
        }elseif ($user->infoSupMedecin->accepter === 0 && $user->is_blocked === 0){
            return response()->json(['message' => 'validation compte en cours'], 200);

        }else{

            $credentials = [
                'email'=>$request->email,
                'password'=>$request->password
            ];


            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'mot de passe ou email invalide'], 401);
            }

            return $this->respondWithToken([
                'user' => new MedecinResource(Auth::user()),
                'token'=>$token
            ]);

        }

    }elseif($user->is_blocked !== 0){
        return response()->json(['error' => 'Votre compte est bloqué'], 404);

    }
    $credentials = [
        'email'=>$request->email,
        'password'=>$request->password
    ];

    if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'mot de passe ou email invalide'], 401);
    }
    return $this->respondWithToken([
       'user' => new PatientResource(Auth::user()),
        'token'=>$token
    ]);
}

public function registerMedecin(StoreMedecinRequest $request){
        try {

        $donneeMedecinValider=$request->validated();
$role = Role::where('nom','medecin')->first();

if($role == null){
    return response()->json(['error' => 'le role n\'existe pas'], 500);

}
        if ($request->file('image') !== null && !$request->file('image')->getError()) {
            $donneeMedecinValider['image'] = $request->file('image')->store('image', 'public');

            $donneeMedecinValider['password']=Hash::make($donneeMedecinValider['password']);

            $donneeMedecinValider['role_id']=$role->id;


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
              'image' => $donneeMedecinValider['image'],
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
        $donneeMedecinValider['role_id']=$role->id;
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
            $role = Role::where('nom','patient')->first();

if($role == null){
    return response()->json(['error' => 'le role n\'existe pas'], 500);

}
            $donneePatientValider['role_id']=$role->id;
            $donneePatientValider['password']=Hash::make($donneePatientValider['password']);

            $patient = User::create($donneePatientValider);
            if($patient){
                return response()->json([
                'message' => 'compte créé avec succès',
                    'user' => new PatientResource($patient),
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                ], 500);
        }

    }


    public function modificationPatient(UpdatePatientRequest $request, User $user){


        try {
            $donneePatientValider=$request->validated();
           if($user->role == null){
                return response()->json([
                    'message' => 'Utilisateur n\'a pas de role'
                   ],500);
            }elseif($user->role->nom === 'medecin' || $user->role->nom ==='admin'){
                return response()->json([
                    'error' => 'non autorisé',
                    ], 403);
            }elseif($user->update($donneePatientValider)){
                return response()->json([
                'message' => 'compte modifié avec sucess',
                    'user' => new PatientResource($user),
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
        $medecin = User::where('id', $id)->first();
        if($medecin->role == null){
            return response()->json([
                'message' => 'Utilisateur n\'a pas de role'
               ],500);
        }elseif($medecin->role->nom === 'patient' || $medecin->role->nom ==='admin'){
            return response()->json([
                'error' => 'non autorisé',
                ], 403);
        }elseif ($request->file('image') !== null && !$request->file('image')->getError()) {
            if($medecin->image){
                Storage::disk('public')->delete($medecin->image);

            }
            $donneeMedecinValider['image'] = $request->file('image')->store('image', 'public');


    $infoSupMedecin =InfoSupMedecin::where('user_id',$id)->first();
            $medecin->update([
                'nom' => $donneeMedecinValider['nom'],
                'telephone' => $donneeMedecinValider['telephone'],
                'genre' => $donneeMedecinValider['genre'],
                'ville_id' => $donneeMedecinValider['ville_id'],

            ]);
             $infoSupMedecin=$infoSupMedecin->update([
                'hopital_id' => $donneeMedecinValider['hopital_id'],
              'secteur_activite_id' => $donneeMedecinValider['secteur_activite_id'],
              'image' => $donneeMedecinValider['image'],


            ]);
            if($medecin && $infoSupMedecin){
                return response()->json([
                 'message' => 'compte modifié',
                 'user' => new MedecinResource($medecin),
                ], 200);
            }

        }

    $infoSupMedecin =InfoSupMedecin::where('user_id',$id)->first();

       // $donneeMedecinValider['role_id']=$medecin->id;
        $medecin->update([
            'nom' => $donneeMedecinValider['nom'],
            'telephone' => $donneeMedecinValider['telephone'],
            'genre' => $donneeMedecinValider['genre'],
            'ville_id' => $donneeMedecinValider['ville_id'],

        ]);

         $infoSupMedecin=$infoSupMedecin->update([
            'hopital_id' => $donneeMedecinValider['hopital_id'],
          'secteur_activite_id' => $donneeMedecinValider['secteur_activite_id'],

        ]);
        if($medecin && $infoSupMedecin){
            return response()->json([
             'message' => 'Compte modifié avec succès',
                'user' => new MedecinResource($medecin),
            ], 200);
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
    public function bloquerUser(User $user){

        try {
            if(!$user->is_blocked){
                $user->is_blocked = true;
                $user->update();
                 return response()->json([
                     'message' => 'utilisateur bloqué avec succès'
                 ],200);
                
            }else{
                return response()->json([
                    'erreur' => 'utilisateur déjà bloqué'
                ],409);
            }


        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Une erreur s\'est produite',
                'details' => $th->getMessage()
            ], 500);
        }



        }

   /**
     * block  medecin .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function debloquerUser(User  $user){

        try {
            if($user->is_blocked){
                $user->is_blocked = false;

               $user->update();

                    return response()->json([
                        'message' => 'utilisateur débloqué avec succès'
                    ],200);
       
                   
            }else{
                return response()->json([
                    'erreur' => 'utilisateur déjà débloqué'
                ],409);
            }




        } catch (\Throwable $th) {
           return response()->json([
                 'erreur' => $th->getMessage()
             ]);
        }


        }


        public function listeMedecin(){
            // Récupérer les utilisateurs avec le rôle de médecin et charger les informations supplémentaires du médecin
            $medecins = User::where('role_id', 2)->with('infoSupMedecin')->get();
            // Vérifier s'il y a des médecins
            if($medecins->all() === false){
                return response()->json([
                    'message' => 'Aucun médecin pour l\'instant'
                ], 204);
            }
        
            // Retourner les médecins avec leurs informations supplémentaires
            return response()->json([
                'medecins' => $medecins
            ], 200);
        }

        public function listePatient(){
            // Récupérer les utilisateurs avec le rôle de médecin et charger les informations supplémentaires du médecin
            $patients = User::where('role_id', 3)->get();
            // Vérifier s'il y a des médecins
            if($patients->all() === false){
                return response()->json([
                    'message' => 'Aucun patient pour l\'instant'
                ], 204);
            }
        
            // Retourner les médecins avec leurs informations supplémentaires
            return response()->json([
                'patients' => $patients
            ], 200);
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
             'user' => new PatientResource(Auth::user()),
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

 public function allUser(){
    $users = User::all();

    return response()->json([
       'users' =>  PatientResource::collection($users)
    ],200);
 }
 
}
