<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMedecinRequest extends FormRequest
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
            'nom' => ['required', 'min:2', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email'],

            'genre' => ['required'],
            'telephone' => ['required', 'regex:/^(70|75|76|77|78)[0-9]{7}$/'],
            'image' => ['image', 'mimes:png,jpeg,jpg'],
            'ville_id' => ['required', 'integer', 'exists:villes,id'],
            'secteur_activite_id' => ['required', 'integer', 'exists:secteur_activites,id'],
            'hopital_id' => ['required', 'integer', 'exists:hopitals,id'],
            
        ];
    }
    
    public function messages()
    {
        return [
            "nom.required" => 'Le nom est requis',
            "nom.min" => 'Le nom doit être composé de lettres, de chiffres et d\'espaces (au moins 2 caractères)',
            "email.required" => 'L\'email est requise',
            "email.email" => 'L\'email incorrecte',

            "image.mimes" => 'Format d\'image incorrecte',
            "genre.required" => 'Le genre est requis',
            "hopital_id.required" => 'L\'hopital est requise',
            
            "ville_id.exists" => 'la ville est introuvable',
            "role_id.exists" => 'le role est introuvable',
            "secteur_activite_id.exists" => 'le secteur d\'activité est introuvable',
            "hopital.exists" => 'le secteur d\'activité est introuvable',
            "ville_id.required" => 'La ville est requise',
            "secteur_activite_id.required" => 'Le secteur d\'activité est requis'



            
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
