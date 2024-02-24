<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRule;


class StorePatientRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:users,email',
            'regex:/^[A-Za-z]+[A-Za-z0-9._%+-]+@+[A-Za-z][A-Za-z0-9.-]+.[A-Za-z]{2,}$/'],
           'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            'password_confirmation' => ['required'],
            'genre' => ['required', 'in:homme,femme'],
            'telephone' => ['required','regex:/^(70|75|76|77|78)[0-9]{7}$/'],
            'ville_id' => ['required', 'Integer','exists:villes,id' ],
            
        ];
    }
    
    public function messages()
    {
        return [
            "nom.required" => 'Le nom est requis',
            "nom.min" => 'Le nom doit être composé de lettres, de chiffres et d\'espaces (au moins 2 caractères)',
            "email.required" => 'L\'email est requise',
            "email.unique" => 'L\'email existe déjà',
            "email.email" => 'L\'email incorrecte',
            "email.regex" => "Format email invalid",


            "password.required" => 'Le mot de passe est requis',
            "password.PasswordRule" => 'Le mot de passe doit avoir au moins 8 caractères, une lettre majuscule
            ,minuscule et un symbole',

            "genre.required" => 'Le genre est requis',
            "genre.in" => 'Le genre doit être soit homme, soit femme',
            "telephone.required" => 'Le numéro de telephone est requis',
            "telephone.regex" => 'Format numéro de telephone invalid (77,78,76,70,75) suivi de 7 chiffres ',

            "password.confirmed" => 'Les mots de passe ne sont pas conforment',


            "password_confirmation.required" => ' le champ confirmation mot de passe est requis',
            "genre.required" => 'Le genre est requis',
            "ville_id.exists" => 'la ville est introuvable',
            "ville_id.required" => 'La ville est requise',
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
