<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRegisterRequest extends FormRequest
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
            // 'employee_name'=>'required',
            // 'employee_password'=>'required',
            // 'employee_address'=>'required',
            // 'employee_phone'=>'required',
            // 'salary'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'employee_name.required'=>'this field is required',
            'employee_password.required'=>'this field is required',
            'employee_address.required'=>'this field is required',
            'employee_phone.required'=>'this field is required',
            'salary.required'=>'this field is required',
        ];
    }
}
