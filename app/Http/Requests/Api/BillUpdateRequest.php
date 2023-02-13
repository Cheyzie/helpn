<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BillUpdateRequest extends FormRequest
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
            'title' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'contacts' => ['nullable', 'string'],
            'city_id' => ['nullable', 'numeric', 'exists:cities,id'],
            'type_id' => ['nullable', 'numeric', 'exists:types,id'],
        ];
    }
}
