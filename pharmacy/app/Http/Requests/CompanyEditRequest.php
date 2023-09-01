<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyEditRequest extends FormRequest
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
            'company_phone'=>'required',
            'line_phone'=>'required',
            'company_owner'=>'required',
            'company_image'=>'required',
        ];
    }

    public function messages(){
        return [
            'company_phone.required'=>'this field is required',
            'company_owner.required'=>'this field is required',
            'line_phone.required'=>'this field is required',
            'company_image.required'=>'this field is required',
        ];
    }
}
