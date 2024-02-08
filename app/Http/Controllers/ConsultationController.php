<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Planning;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcceptationConsultation;
use App\Http\Requests\StoreConsultationRequest;
use App\Http\Requests\UpdateConsultationRequest;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //  // Récupérer le médecin connecté
        //  $medecin = Auth::user();

        //  // Récupérer les consultations du médecin
        //  $consultations = $medecin->consultations;
     
        //  if ($consultations->isNotEmpty()) {
        //      return response()->json([
        //          'message' => 'Liste de demandes de consultation',
        //          'consultations' => $consultations
        //      ], 200);
        //  } else {
        //      return response()->json([
        //          'message' => 'Aucune consultation pour l\'instant'
        //      ], 200);
        //  }

         $medecin = Auth::user();

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
    public function accepterConsultation(string $id)
    {
       try {
           $consultation = Consultation::findOrFail($id);
           $this->authorize('accepterConsultation', $consultation);

       if($consultation){
        $consultation->status='accepter';
        if($consultation->update()){
            
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
        }
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
        $donneeConsultationValider= $request->validated();
        $donneeConsultationValider['user_id']= Auth::user()->id;
        $planning = Planning::where('id',$donneeConsultationValider['planning_id'] )->first();
        if($planning->is_deleted === 0){

            $consultation= new Consultation($donneeConsultationValider);

            if($consultation->save()){
                return response()->json([
                    'message' => 'votre demande a bien été pris en compte',
                    'consultation' => $consultation
                ],201);
            }else{
                return response()->json([
                    'message' => 'votre demande non pris en compte'
                ],500);
            }
        }else{
            return response()->json([
                'erreur' => 'article non trouvé'
            ],404);

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
    public function edit(Consultation $consultation)
    {
        //
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
