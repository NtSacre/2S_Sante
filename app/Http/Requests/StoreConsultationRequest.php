<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
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
    public function rules()
    {
        return [
            'date' => 'required|date',
            'planning_id' => 'required|exists:plannings,id',
            'heure' => 'required|date_format:H:i',
            'motif' => 'required|in:Consultation_generale,Prescription_de_médicaments_renouvelables,Suivi_de_traitement,Conseils_sur_des_symptomes_mineurs,Medecine_preventive,Problemes_de_sante_mentale,Deuxieme_avis_medical,Suivi_post_operatoire,Question_de_sante_sexuelle',
            'type' => 'required|in:en_ligne,presentiel',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'La date de la consultation est obligatoire.',
            'date.date' => 'La date de la consultation doit être une date valide.',
            'planning_id.required' => 'L\'identifiant du planning est obligatoire.',
            'planning_id.exists' => 'Le planning sélectionné n\'existe pas.',
            'heure.required' => 'L\'heure de la consultation est obligatoire.',
            'heure.date_format' => 'L\'heure de la consultation doit être au format HH:MM.',
            'motif.required' => 'Le motif de la consultation est obligatoire.',
            'motif.in' => 'Le motif de la consultation doit être parmi les options fournies.',
            'type.required' => 'Le type de consultation est obligatoire.',
            'type.in' => 'Le type de consultation doit être soit "en ligne" soit "présentiel".',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors,
        ], 422));
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date = $this->input('date');
            if ($date <= now()->format('Y-m-d')) {
                $validator->errors()->add('date', 'La date de la consultation doit être postérieure à la date d\'aujourd\'hui.');
            }
        });
    }
}
