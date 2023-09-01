<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientOrderRequest extends FormRequest
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
            'co_description'=>'required',
            'address'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'co_description.required'=>'this field is required',
            'address.required'=>'this field is required',
        ];
    }
}
