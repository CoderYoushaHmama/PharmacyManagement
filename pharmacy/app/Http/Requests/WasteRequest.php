<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WasteRequest extends FormRequest
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
            'w_descriptions'=>'required',
            'wastes_number'=>'required|unique:wastes,wastes_number',
        ];
    }

    public function messages()
    {
        return [
            'w_descriptions.required'=>'this field is required',
            'wastes_number.required'=>'this field is required',
            'wastes_number.unique'=>'this field is unique',
        ];
    }
}
