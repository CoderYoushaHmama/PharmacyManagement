<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierEditRequest extends FormRequest
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
            // 'supplier_name'=>'required',
            // 'supplier_image'=>'required',
            // 'supplier_phone'=>'required',
            // 'supplier_description'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'supplier_name.required'=>'this field is required',
            'supplier_image.required'=>'this field is required',
            'supplier_phone.required'=>'this field is required',
            'supplier_description.required'=>'this field is required',
        ];
    }
}
