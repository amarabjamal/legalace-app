<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreInvoicePaymentRequest extends FormRequest
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
            'company_id' => auth()->user()->company_id,
            'created_by_user_id' => auth()->id(),
            'amount' => $this->invoice->grand_total,
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
            'date' => ['required', 'date', 'before_or_equal:today'],
            'client_bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'payment_method_code' => ['required', new Enum(PaymentMethodEnum::class)],
            'receipt' => ['required', 'file', 'mimes:pdf,xlxs,xlx,docx,doc,csv,txt,png,gif,jpg,jpeg', 'max:2048'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
