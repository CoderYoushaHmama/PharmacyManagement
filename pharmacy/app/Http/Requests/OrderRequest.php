<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'company_id'=>'required',
            'order_number'=>'required',
            'order_description'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'company_id.required'=>'this field is required',
            'supplier_id.required'=>'this field is required',
            'order_number.required'=>'this field is required',
            'order_description.required'=>'this field is required',
        ];
    }
}
