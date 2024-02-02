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
    public function rules(): array
    {
        return [
            "jour" => ['required', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche'],
            "heure_debut" => ['required','date_format:H:i:s'],
            "heure_fin" => ['required', 'date_format:H:i:s']
            

        ];
    }

    public function messages()
    {
        return [
            "jour.required" => 'Le champs jour est requis',
            "jour.in" => 'Le jour doit être de format (e.g: Lundi, Mardi...) ',
            "heure_debut.required" => 'L\'heure de debut est obligatoire',
            "heure_fin.required" => 'L\'heure de fin est obligatoire',
            "heure_debut.date_format" => 'Format heure de debut  incorrect',
            "heure_fin.date_format" => 'Format heure de fin incorrect'
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