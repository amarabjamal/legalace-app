<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimVoucherApprovalRequest extends FormRequest
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
            'company_id' => auth()->user()->company->id,
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
            'claim_voucher_id' => ['required', 'exists:claim_vouchers,id'],
            'pay_from_bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
