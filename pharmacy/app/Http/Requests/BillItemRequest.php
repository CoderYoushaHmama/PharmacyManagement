<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillItemRequest extends FormRequest
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
            'content_id'=>'required',
            'quantity'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'content_id'=>'this field is required',
            'quantity'=>'this field is required',
        ];
    }
}
