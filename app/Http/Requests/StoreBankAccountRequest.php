<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBankAccountRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'created_by_user_id' => auth()->id(),
            'company_id' => auth()->user()->company_id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'label' => [
                'required',
                'string',
                Rule::unique('bank_accounts')->where(fn($query) => $query->where('company_id', auth()->user()->company_id)),
            ],
            'bank_account_type_id' => ['required', 'exists:bank_account_types,id'],
            'account_name' => ['required', 'string'],
            'account_number' => [
                'required',
                'numeric',
                'digits_between:6,17',
                Rule::unique('bank_accounts')->where(fn($query) => $query->where('company_id', auth()->user()->company_id)),
            ],
            'swift_code' => ['required', 'string', 'regex:/^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$/'],
            'opening_balance' => ['required', 'numeric', 'decimal:2', 'between:0,999999999.99'],
            'bank_name' => ['required', 'string', 'exclude:Other'],
            'custom_bank_name' => 'nullable|string',
            'bank_address' => ['required', 'string'],
        ];
    }
}
