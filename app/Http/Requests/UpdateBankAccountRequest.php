<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateBankAccountRequest extends FormRequest
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
            'label' => [
                'required', 
                'string', 
                Rule::unique('bank_accounts')->ignore($this->bank_account)->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))
            ],
            'account_name' => [
                'required', 
                'string'
            ],
            'bank_name' => [
                'required', 
                'string'
            ],
            'account_number' => [
                'required', 
                'numeric', 
                'digits_between:6,17', 
                Rule::unique('bank_accounts')->ignore($this->bank_account)->where(fn ($query) => $query->where('company_id', Auth::user()->company_id))
            ],
            'bank_address' => [
                'required', 
                'string'
            ],
            'swift_code' => [
                'required', 
                'string', 
                'regex:/^([a-zA-Z]){4}([a-zA-Z]){2}([0-9a-zA-Z]){2}([0-9a-zA-Z]{3})?$/'
            ],
            'bank_account_type_id' => [
                'required', 
                'exists:bank_account_types,id'
            ],
        ];
    }
}
