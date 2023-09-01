<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterRequest extends FormRequest
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
            // 'company_name'=>'required|unique:companies,company_name',
            // 'company_phone'=>'required|unique:companies,company_phone',
            // 'line_phone'=>'required|unique:companies,line_phone',
            // 'company_address'=>'required',
            // 'email'=>'required',
            // 'password'=>'required|min:8',
            // 'company_owner'=>'required',
            // 'establishment_no'=>'required|unique:companies,establishment_no',
            // 'es_image'=>'required',
        ];
    }

    public function messages(){
        return [
            'location_id.required'=>'this field is required',
            'company_name.required'=>'this field is required',
            'company_name.unique'=>'this field is unique',
            'company_phone.required'=>'this field is required',
            'company_phone.unique'=>'this field is unique',
            'email.required'=>'this field is required',
            'email.unique'=>'this field is unique',
            'password.required'=>'this field is required',
            'password.min'=>'this field is min 8',
            'company_owner.required'=>'this field is required',
            'establishment_no.required'=>'this field is required',
            'establishment_no.unique'=>'this field is unique',
            'es_image.required'=>'this field is required',
            'company_address.required'=>'this field is required',
            'line_phone.required'=>'this field is required',
            'line_phone.unique'=>'this field is unique',
        ];
    }
}
