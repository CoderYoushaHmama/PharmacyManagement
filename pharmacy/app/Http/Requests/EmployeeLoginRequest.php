<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'employee_email'=>'required',
            'employee_password'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'employee_email.required'=>'this field is required',
            'employee_password.required'=>'this field is required',
        ];
    }
}
