<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFirmAccountRequest extends FormRequest
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
            'date' => [
                'required', 
                'string', 
            ],
            'description' => [
                'required', 
                'string'
            ],
            'transaction_type' => [
                'required', 
                'string'
            ],
            'debit' => [
                'required',
                'numeric', 
                'digits_between:1,17'
            ],
            'credit' => [
                'required', 
                'numeric'
            ],
            'balance' => [
                'required', 
                'numeric', 
            ],
            // 'bank_account_type_id' => [
            //     'required', 
            //     'exists:bank_account_types,id'
            // ],
        ];
    }


    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated(), [
            'created_by' => Auth::id(),
            // 'company_id' => Auth::user()->company_id,
        ]);
    }
}
