<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class StoreRoleRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        return true;
    }

    // Prepare the data for validation.
    protected function prepareForValidation()
    {
        // $this->merge([
        //     'slug' => Str::slug($this->slug),
        // ]);
    }

    //  Get the validation rules that apply to the request.
    public function rules()
    {
        return ['name' => 'required|string|min:4|unique:roles,name'];
    }

    // Customizing the error messages.
    public function messages()
    {
        return [
            'name.required' => 'The role name field is required.',
            'name.unique' => 'The role name has already been taken.',
            'name.min' => 'The role name must contain at least four (4) characters.'
        ];
    }

    // Customize JSON response for the failed validation (Applicable for api requests only).
    public function failedValidation(Validator $validator)
    {
        $content = [
            'message' => 'Failed to create role due to validation error(s).',
            'data' => null,
            'error' =>  $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($content, 400));
    }

    /** 
     * ! Applicable for web routes only.
     * 
     * The URI that users should be redirected to if validation fails.
     * By default user is redirected to the previous location if valiadation fails.
     */
    // protected $redirect = '/dashboard';

    // For named routes 
    // protected $redirectRoute = 'dashboard';
}
