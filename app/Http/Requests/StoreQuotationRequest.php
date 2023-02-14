<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreQuotationRequest extends FormRequest
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
            'deposit_amount' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'case_file_id' => ['required', 'exists:case_files,id', 'unique:case_files,id'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id']
        ];
    }

    public function validated($key = null, $default = null)
    {
        return array_merge(parent::validated(), [
            'is_paid' => false,
            'issued_by' => Auth::id(),
        ]);
    }
}
