<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePlanningRequest extends FormRequest
{
 /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
     {
         return [
             'jour' => 'required|string|in:lundi,mardi,mercredi,jeudi,vendredi,samedi,dimanche',
             'creneaux' => 'required|array|min:1|max:2',
             'creneaux.*.heure_debut' => 'required|date_format:H:i',
             'creneaux.*.heure_fin' => 'required|date_format:H:i|after:creneaux.*.heure_debut',
             'creneaux.1.heure_debut' => 'nullable|after:creneaux.0.heure_fin',
         ];
     }
     
     public function messages()
     {
         return [
             'jour.required' => 'Le jour est obligatoire.',
             'jour.in' => 'Le jour doit être un jour de la semaine valide.',
             'creneaux.required' => 'Le tableau des créneaux est obligatoire.',
             'creneaux.array' => 'Le tableau des créneaux doit être un tableau.',
             'creneaux.min' => 'Le tableau des créneaux doit avoir au moins :min élément.',
             'creneaux.max' => 'Le tableau des créneaux ne peut pas avoir plus de :max éléments.',
             'creneaux.*.heure_debut.required' => 'L\'heure de début est obligatoire pour chaque créneau.',
             'creneaux.*.heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM.',
             'creneaux.*.heure_fin.required' => 'L\'heure de fin est obligatoire pour chaque créneau.',
             'creneaux.*.heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM.',
             'creneaux.*.heure_fin.after' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
             'creneaux.1.heure_debut.after' => 'L\'heure de début du deuxième créneau doit être postérieure à l\'heure de fin du premier créneau.',
         ];
     }
    
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
    
        throw new HttpResponseException(response()->json([
            'errors' => $errors,
        ], 422));
    }
}
