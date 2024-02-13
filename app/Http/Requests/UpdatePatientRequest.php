<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePatientRequest extends FormRequest
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
            'nom' => [ 'min:2', 'regex:/^[a-zA-Z\s]+$/'],

            'genre' => [ 'in:homme,femme'],
            'telephone' => ['regex:/^(70|75|76|77|78)[0-9]{7}$/'],
            'ville_id' => ['Integer','exists:villes,id' ],
            
        ];
    }
    
    public function messages()
    {
        return [
            "nom.min" => 'Le nom doit être composé de lettres, de chiffres et d\'espaces (au moins 2 caractères)',

            'telephone.regex' => 'Format numéro telephone invalid',
            "genre.in" => 'Le genre doit être soit homme, soit femme',
            "ville_id.exists" => 'la ville est introuvable',
            "ville_id.integer" => 'la ville  doit être de type integer',




            
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
