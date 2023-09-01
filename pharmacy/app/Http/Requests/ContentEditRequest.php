<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentEditRequest extends FormRequest
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
            'min'=>'required',
            'max'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'min.required'=>'this field is required',
            'max.required'=>'this field is required',
        ];
    }
}
