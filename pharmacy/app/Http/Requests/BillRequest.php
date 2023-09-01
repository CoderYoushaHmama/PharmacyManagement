<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
            'client_name'=>'required',
            'bill_number'=>'required',
            'delivery_cost'=>'required',
            'is_return'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'client_id.required'=>'this field is required',
            'bill_number.required'=>'this field is required',
            'delivery_cost.required'=>'this field is required',
            'is_return.required'=>'this field is required',
        ];
    }
}
