<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailsRequest extends FormRequest
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
            // 'day'=>'required',
            // 'open'=>'required',
            // 'close'=>'required',
            // 'is_duty'=>'required',
        ];
    }

    public function messages(){
        return [
            'day.required'=>'this field is required',
            'open.required'=>'this field is required',
            'close.required'=>'this field is required',
            'is_duty.required'=>'this field is required',
        ];
    }
}
