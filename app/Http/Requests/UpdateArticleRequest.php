<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateArticleRequest extends FormRequest
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
            "titre" => ['required', 'string', 'max:255', 'min:2'],
            "description" => ['required', 'string', 'min:4'],
            "image" => ['required', 'image', 'mimes:png,jpeg,jpg'],

        ];
    }

    public function messages()
    {
        return [
            "titre.required" => 'Le titre est requis',
            "titre.min" => 'Le titre doit être composé de lettres, de chiffres et d\'espaces (au moins 2 caractères)',
            "description.required" => 'La description est requise',
            "description.min" => 'La description doit au  moins avoir 4 caractères',
            "image.required" => 'L\'image est requise',
            "image.image" => 'Le champ image doit etre un image',
            "image.mimes" => 'Format image incorrect'


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
