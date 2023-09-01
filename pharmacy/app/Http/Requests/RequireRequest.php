<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequireRequest extends FormRequest
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
            'material_id'=>'required',
            'quantity'=>'required',
            'description'=>'required',
            'require_number'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'material_id.required'=>'this field is required',
            'quantity.required'=>'this field is required',
            'description.required'=>'this field is required',
            'require_number.required'=>'this field is required',
        ];
    }
}
