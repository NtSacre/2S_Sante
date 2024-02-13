<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSecteurActiviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

       /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            "nom" => ['required','string','max:255', 'min:2']

        ];
    }
    public function messages()
    {
        return [
            "nom.required" => 'Le nom est requis',
            "nom.min" => 'Le nom doit au minimum contenir 2 caractères',

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
