<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
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
            'material_name'=>'required|unique:materials,material_name',
            'scientific_name'=>'required|unique:materials,scientific_name',
            'material_image'=>'required',
            'price_pharmacy'=>'required',
            'price_sell'=>'required',
            'qr_code'=>'required',
            'license_image'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'material_name.required'=>'this field is required',
            'scientific_name.required'=>'this field is required',
            'material_name.unique'=>'this field is unique',
            'scientific_name.unique'=>'this field is unique',
            'material_image.required'=>'this field is required',
            'price_pharmacy.required'=>'this field is required',
            'price_sell.required'=>'this field is required',
            'qr_code.required'=>'this field is required',
            'license_image.required'=>'this field is required',
        ];
    }
}
