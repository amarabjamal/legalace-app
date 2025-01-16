<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'id_type_id' => ['required'],
            'id_number' => ['required', 'string', 'min:9', 'max:13'],
            'phone_number' => ['required', 'numeric', 'digits_between:9,12'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_address' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'outstanding_balance' => ['nullable', 'numeric'],
            'linked_client_account' => ['nullable'],
        ];
    }

    public function fails() {}
}
