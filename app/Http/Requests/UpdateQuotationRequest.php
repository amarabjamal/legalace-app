<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuotationRequest extends FormRequest
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
            'work_descriptions' => ['required', 'array'],
            'work_descriptions.*.description' => ['required', 'string'],
            'work_descriptions.*.fee' => ['required', 'numeric', 'decimal:2', 'between:0.01,999999999.99'],
            'deposit_amount' => ['required', 'numeric', 'decimal:2', 'between:0.01,999999999.99'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id']
        ];
    }

    public function messages()
    {
        return [
            'work_descriptions.required' => 'The item list cannot be empty.',
            'work_descriptions.*.description.required' => 'The work desc :position cannot be empty.',
            'work_descriptions.*.fee.between' => 'The work fee :position must be between 0.01 and 999999999.99.',
        ];
    }
}
