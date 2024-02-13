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
            'nom' => ['min:2', 'regex:/^[a-zA-Z\s]+$/'],

            'genre' => [ 'in:homme,femme'],
            'telephone' => ['regex:/^(70|75|76|77|78)[0-9]{7}$/'],
            'image' => ['image', 'mimes:png,jpeg,jpg'],
            'ville_id' => ['integer', 'exists:villes,id'],
            'secteur_activite_id' => ['integer', 'exists:secteur_activites,id'],
            'hopital_id' => ['integer', 'exists:hopitals,id'],
            
        ];
    }
    
    public function messages()
    {
        return [
            "nom.regex" => 'le nom doit être composé de lettres uniquement',
            "nom.min" => 'Le nom doit être composé de lettres, de chiffres et d\'espaces (au moins 2 caractères)',
        

            "image.mimes" => 'Format d\'image incorrecte',
            "genre.in" => 'Le genre doit être soit homme, soit femme',
            
            "ville_id.exists" => 'la ville est introuvable',
            "secteur_activite_id.exists" => 'le secteur d\'activité est introuvable',
            "hopital.exists" => 'le secteur d\'activité est introuvable',
            "ville_id.integer" => 'la ville  doit être de type integer',
            "secteur_activite_id.integer" => 'secteur activite  doit être de type integer',
            "hopital_id.integer" => 'l\'Hopital doit être de type integer',

            



            
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
