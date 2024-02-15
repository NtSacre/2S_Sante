<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanningRequest extends FormRequest
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
            "date" => ['required', 'date'],
            "heure_debut" => ['required','date_format:H:i'],
            "heure_fin" => ['required', 'date_format:H:i']


        ];
    }

    public function messages()
    {
        return [
            "date.required" => 'Le champs date est requis',
            "date.date" => 'La date doit être de format date',
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
