<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRegisterRequest extends FormRequest
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
            // 'location_id'=>'required',
            // 'client_name'=>'required',
            // 'email'=>'required|unique:clients,email',
            // 'password'=>'required',
            // 'client_phone'=>'required',
            // 'client_description'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'location_id.required'=>'this field is required',
            'client_name.required'=>'this field is required',
            'email.required'=>'this field is required',
            'email.unique'=>'this field is unique',
            'password.required'=>'this field is required',
            'client_phone.required'=>'this field is required',
            'client_description.required'=>'this field is required',
        ];
    }
}