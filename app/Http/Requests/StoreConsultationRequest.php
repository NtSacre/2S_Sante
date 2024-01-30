<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreConsultationRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'planning_id' => ['required', 'Integer', 'exists:plannings,id'],
            
            "heure" => ['required','date_format:H:i:s'],
            "motif" => ['required', 'in:Consultation_generale,
            Prescription_de_médicaments_renouvelables,Suivi_de_traitement,
            Conseils_sur_des_symptomes_mineurs,
            Medecine_preventive,Problemes_de_sante_mentale,
            Deuxieme_avis_medical,Suivi_post_operatoire,
            Question_de_sante_sexuelle'],


        ];
    }
    public function messages()
    {
        return [
            "planning_id.required" => 'Le planning est requis',
            "planning_id.exists" => 'Le planning non trouvé',
            "heure.required" => 'L\'heure est requise',
            "heure.date_format" => 'Format heure incorrect',
            "motif.required" => 'Le motif est requis',
            "motif.in" => 'Le motif non trouvé'
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
