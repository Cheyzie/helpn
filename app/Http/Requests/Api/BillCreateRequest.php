<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BillCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'details' => ['required', 'string'],
            'contacts' => ['required', 'string'],
            'city_id' => ['required', 'numeric', 'exists:cities,id'],
            'type_id' => ['required', 'numeric', 'exists:types,id'],
        ];
    }

    public function messages()
    {
        return [
            "city_id.exists" => "There is no such city",
            "type_id.exists" => "There is no such type",
        ];
    }
}
