<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'location_id'=>'required',
            'stock_name'=>'required',
            'space'=>'required',
            'temp'=>'required',
        ];
    }

    public function messages()
    {
        return  [
            'location_id.required'=>'this field is required',
            'stock_name.required'=>'this field is required',
            'space.required'=>'this field is required',
            'temp.required'=>'this field is required',
        ];
    }
}