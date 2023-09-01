<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyEditRequest extends FormRequest
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
            'pharmacy_name'=>'required',
            'pharmacy_password'=>'required|min:8',                     
            'pharmacy_image'=>'required',                                   
            'pharmacy_owner'=>'required',         
        ];
    }

    public function messages(){
        return [
            'pharmacy_name.required'=>'this field is required',
            'pharmacy_password.required'=>'this field is required',
            'pharmacy_phone.required'=>'this field is required',
            'pharmacy_phone.unique'=>'this field is unique',
            'line_phone.unique'=>'this field is unique',
            'line_phone.required'=>'this field is required',
            'pharmacy_image.required'=>'this field is required',
            'pharmacy_owner.required'=>'this field is required',
        ];
    }
}
