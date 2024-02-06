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
            'nom' => ['required', 'min:2', 'regex:/^[a-zA-Z\s]+$/'],

            'genre' => ['required'],
            'telephone' => ['required','regex:/^(70|75|76|77|78)[0-9]{7}$/'],
            'ville_id' => ['required', 'Integer','exists:villes,id' ],
            
        ];
    }
    
    public function messages()
    {
        return [
            "nom.required" => 'Le nom est requis',
            "nom.min" => 'Le nom doit être composé de lettres, de chiffres et d\'espaces (au moins 2 caractères)',

            'telephone.regex' => 'Format numéro telephone invalid',
            "genre.required" => 'Le genre est requis',
            "ville_id.exists" => 'la ville est introuvable',
            "ville_id.required" => 'La ville est requise',



            
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
