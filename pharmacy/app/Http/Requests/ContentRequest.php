<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
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
            // 'material_id'=>'required',
            // 'batch_id'=>'required',
            // 'quantity'=>'required',
            // 'min'=>'required',
            // 'max'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'material_id.required'=>'this field is required',
            'batch_id.required'=>'this field is required',
            'quantity.required'=>'this field is required',
            'min.required'=>'this field is required',
            'max.required'=>'this field is required',
        ];
    }
}
