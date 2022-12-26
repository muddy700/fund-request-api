<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRolePermissionRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        return true;
    }

    //  Get the validation rules that apply to the request.
    public function rules()
    {
        return [
            'role_id' => 'required|uuid|exists:roles,id',
            'permission_id' => 'required|uuid|exists:permissions,id|',
            'created_by' => 'sometimes|uuid|exists:users,id|',
        ];
    }

    // Customize JSON response for the failed validation (Applicable for api requests only).
    public function failedValidation(Validator $validator)
    {
        $content = [
            'message' => 'Validation error(s).',
            'data' => null,
            'error' =>  $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($content, 400));
    }
}