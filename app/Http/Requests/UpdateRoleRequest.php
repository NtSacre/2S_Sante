<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRoleRequest extends FormRequest
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
            'nom' => ['required','string','min:2'],
            'description' => ['required','string','max:255'],
            
           
        ];
    }
    public function failedValidation(validator $validator ){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'status_code'=>422,
            'error'=>true,
            'message'=>'erreur de validation',
            'errorList'=>$validator->errors()
        ]));
    }
    public function messages(): array
    {
        return [
            'nom.required'=> 'Le champs nom est obligatoire',
            'description.required'=> 'Le champs description est obligatoire',

            
            
        ];
    }
}
