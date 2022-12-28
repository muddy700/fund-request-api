<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ManageRolePermissionRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        return true;
    }

    // Prepare the data for validation.
    protected function prepareForValidation()
    {
        // Role id from path parameter
        $role_id = $this->route('role_id') ?? null;

        // Role id from request body
        $role = $this->input('role_id') ?? null;


        if (!$role && $this->isJson())
            $this['role_id'] = $role_id;
        else if (!$role && !$this->isJson())
            $this->request->add(['role_id' => $role_id]);
        else
            $this->merge(['role_id' => $role_id]);
    }

    //  Get the validation rules that apply to the request.
    public function rules()
    {
        return [
            'flag' => 'required|integer|between:1,2',
            'role_id' => 'required|uuid|exists:roles,id',
            'active_permissions' => 'array|required_if:flag,1',

            'added_permissions' => ['array', Rule::requiredIf(fn () => $this->flag == 2 && !$this->removed_permissions)],
            'removed_permissions' => ['array', Rule::requiredIf(fn () => $this->flag == 2 && !$this->added_permissions)],
        ];
    }

    // Customizing the error messages.
    public function messages()
    {
        return [
            'role_id.exists' => 'No role found with id: ' . $this->route('role_id'),

            'added_permissions.array' => 'The added_permissions field must be an array.',
            'added_permissions.required' => 'The added_permissions field is required when flag is 2 and no removed_permissions field.',

            'removed_permissions.array' => 'The removed_permissions field must be an array.',
            'removed_permissions.required' => 'The removed_permissions field is required when flag is 2 and no added_permissions field.',
        ];
    }

    // Customizing the attribute names in error messages.
    public function attributes()
    {
        return [
            'active_permissions' => 'active_permissions',
            'added_permissions' => 'added_permissions',
            'removed_permissions' => 'removed_permissions',
        ];
    }

    // Customize JSON response for the failed validation (Applicable for api requests only).
    public function failedValidation(Validator $validator)
    {
        $content = [
            'message' => 'Failed to manage role-permissions due to validation error(s). 🥺',
            'data' => null,
            'error' =>  $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($content, 400));
    }
}
