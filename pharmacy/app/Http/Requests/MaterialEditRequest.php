<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialEditRequest extends FormRequest
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
            // 'material_image'=>'required',
            // 'price_pharmacy'=>'required',
            // 'price_sell'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'material_image.required'=>'this field is required',
            'price_pharmacy.required'=>'this field is required',
            'price_sell.required'=>'this field is required',
        ];
    }
}
