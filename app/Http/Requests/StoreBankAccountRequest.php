<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
                'unique:bank_accounts'
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
                'unique:bank_accounts', 
                'numeric', 
                'digits_between:6,17'
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


    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated(), [
            'created_by' => Auth::id(),
            'company_id' => Auth::user()->company_id,
        ]);
    }
}
