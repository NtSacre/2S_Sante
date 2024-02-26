<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefusConsultationRequest;
use App\Models\User;
use App\Models\Planning;
use App\Models\Consultation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcceptationConsultation;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreConsultationRequest;
use App\Http\Requests\UpdateConsultationRequest;
use App\Mail\RefusConsultation;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $consultations = Consultation::whereHas('planning', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['user:id,nom'])
        ->get();
 
     if ($consultations->isNotEmpty()) {
         return response()->json([
             'message' => 'Liste de demandes de consultation',
             'consultations' => $consultations
         ], 200);
     } else {
         return response()->json([
             'message' => 'Aucune consultation pour l\'instant'
         ], 200);
     }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function accepterConsultation(Consultation $consultation)
    {
       try {
           $this->authorize('consultationMedecin', $consultation);

      if($consultation->status != 'accepter' && $consultation->etat != 'effectuer'){
        $consultation->status='accepter';
        $consultation->update();
            
            $patient= User::findOrFail($consultation->user_id);
            
            $medecin= User::findOrFail($consultation->planning->user_id);
            $planning= $consultation->planning;
          
           
            Mail::to($patient->email)
            ->send(new AcceptationConsultation($planning,
            $consultation, $medecin, $patient));

            return response()->json([
                'message' => 'vous venez d\'accepter cette consulation',
                'details' =>[
                   
                    'consultation'=>$consultation,

                ]
            ], 200);
      }else{
        return response()->json([
            'erreur' => 'consultation déjà acceptée ou effectuée',

        ], 409);
      }

        

       } catch (\Throwable $th) {
        return response()->json([
            'error' => $th->getMessage(),
            
        ], 500);
       }
        
    }



    public function refusConsultation(Consultation $consultation)
    {
       try {


           $this->authorize('consultationMedecin', $consultation);
      if($consultation->status != 'refuser' && $consultation->etat != 'effectuer'){
        $consultation->status='refuser';
        $consultation->update();
            
            $patient= User::findOrFail($consultation->user_id);
            
          
           
            Mail::to($patient->email)
            ->send(new RefusConsultation($consultation, $patient));

            return response()->json([
                'message' => 'vous venez de refuser cette consulation',

            ], 200);
      }else{
       

            return response()->json([
                'erreur' => 'consultation déjà refusée ou effectuée',

            ], 409);
      }
       
        
    

       } catch (\Throwable $th) {
        return response()->json([
            'error' => $th->getMessage(),
            
        ], 500);
       }
        
    }


    public function effectuerConsultation(Consultation $consultation)
    {
       try {


           $this->authorize('consultationMedecin', $consultation);
      if($consultation->etat != 'effectuer'){

        $consultation->etat='effectuer';
        $consultation->update();
            


            return response()->json([
                'message' => 'Consulation marquer comme effectué',

            ], 200);
      }else{
     
            


            return response()->json([
                'erreur' => 'Consulation déjà marqué comme effectué',

            ], 409);
      }

        
    

       } catch (\Throwable $th) {
        return response()->json([
            'error' => $th->getMessage(),
            
        ], 500);
       }
        
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsultationRequest $request)
    {
        $this->authorize('create', Consultation::class);
    
        $donneeConsultationValider = $request->validated();
        $donneeConsultationValider['user_id'] = Auth::user()->id;
            // Vérifier si une consultation existe déjà pour le même jour et le même utilisateur
    $existingConsultation = Consultation::where('user_id', $donneeConsultationValider['user_id'])
    ->whereDate('date', $donneeConsultationValider['date'])
    ->exists();

if ($existingConsultation) {
return response()->json(['erreur' => 'Vous avez déjà une consultation prévue pour ce jour.'], 400);
}
    
        $planning = Planning::findOrFail($donneeConsultationValider['planning_id']);
    
        if ($planning->is_deleted === 1) {
            return response()->json(['erreur' => 'planning non trouvé'], 404);
        }
        if ($planning->jour != Carbon::parse($donneeConsultationValider['date'])->isoFormat('dddd')) {
            return response()->json(['erreur' => 'La date de la consultation doit correspondre au jour du planning.'], 400);
        }



        $creneaux = json_decode($planning->creneaux, true);
        $heureRDV = $donneeConsultationValider['heure'];
        
        // Vérifier si l'heure de rendez-vous est en dehors de toutes les plages horaires du planning
        $heureValide = false;
        foreach ($creneaux as $creneau) {
            if ($heureRDV >= $creneau['heure_debut'] && $heureRDV <= $creneau['heure_fin']) {
                $heureValide = true;
                break;
            }
        }
    
        if (!$heureValide) {
            return response()->json(['erreur' => 'La consultation est en dehors des heures du planning'], 400);
        }
  
        $consultation = new Consultation($donneeConsultationValider);
    
        if ($consultation->save()) {
            return response()->json([
                'message' => 'Votre demande a bien été prise en compte.',
                'consultation' => $consultation
            ], 201);
        } else {
            return response()->json(['erreur' => 'Votre demande n\'a pas pu être prise en compte.'], 500);
        }
    }
    

    

    /**
     * Display the specified resource.
     */
    public function contacterPatient(string $id)
    {
            try {
                $user= User::findOrFail($id);
           
                $numeroWhatsApp=$user->telephone;
                $messageWhatsappEnvoye= "https://api.whatsapp.com/send?phone=$numeroWhatsApp";
                return redirect()->to($messageWhatsappEnvoye);
            } catch (\Throwable $th) {
                return response()->json([
                    'erreur' => $th->getMessage()
                ],500);
            }



        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function listeConsultationPatient()
    {
        $consultation = Consultation::where('user_id',auth()->user()->id)->get();
       

        if($consultation->all() == null){
            return response()->json([
                'message' => 'aucune consultation pour l\'instant'
            ], 200);
        }

        return response()->json([
            'consultations' => $consultation
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsultationRequest $request, Consultation $consultation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        //
    }
}
