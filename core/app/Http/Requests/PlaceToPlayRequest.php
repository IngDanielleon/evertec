<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceToPlayRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "payment_platform" => 'required',
            "reference" => 'nullable',
            "description" => 'nullable|alpha',
            "currency" => 'nullable',
            "total" => 'required',
            "request_id" => 'nullable',
            "status" => 'required',
        ];
    }
}
