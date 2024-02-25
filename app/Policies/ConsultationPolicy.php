<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\Planning;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConsultationPolicy
{


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return  $user->id !== null && $user->role->nom == "patient";
    }

    /**
     * Determine whether the user can update the model.
     */
    public function consultationMedecin(User $user, Consultation $consultation): bool
    {
        $planning = Planning::where('id', $consultation->planning_id)->first();
       
        return $user->role->nom === 'medecin' && $user->id == $planning->user_id;
    }





}
