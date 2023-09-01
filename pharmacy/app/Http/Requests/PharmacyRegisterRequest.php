<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PharmacyRegisterRequest extends FormRequest
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
            // 'pharmacy_name'=>'required',
            // 'pharmacy_email'=>'required|unique:pharmacies,email',
            // 'pharmacy_password'=>'required|min:8',            
            // 'pharmacy_phone'=>'required|unique:pharmacies,pharmacy_phone',            
            // 'line_phone'=>'required|unique:pharmacies,line_phone',            
            // 'pharmacy_image'=>'required',            
            // 'no_facility'=>'required|unique:pharmacies,no_facility',            
            // 'no_image'=>'required',            
            // 'pharmacy_owner'=>'required',            
            // 'pharmacy_address'=>'required',            
        ];
    }

    public function messages(){
        return [
            'location_id.required'=>'this field is required',
            'pharmacy_name.required'=>'this field is required',
            'pharmacy_email.required'=>'this field is required',
            'pharmacy_email.unique'=>'this field is unique',
            'pharmacy_password.required'=>'this field is required',
            'pharmacy_phone.required'=>'this field is required',
            'pharmacy_phone.unique'=>'this field is unique',
            'line_phone.unique'=>'this field is unique',
            'line_phone.required'=>'this field is required',
            'pharmacy_image.required'=>'this field is required',
            'no_facility.required'=>'this field is required',
            'no_image.required'=>'this field is required',
            'no_facility.unique'=>'this field is unique',
            'pharmacy_owner.required'=>'this field is required',
        ];
    }
}