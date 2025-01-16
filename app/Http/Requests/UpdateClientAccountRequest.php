<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateClientAccountRequest extends FormRequest
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
            'date' => ['required', 'date'], // Ensure the date is valid
            'bank_account_id' => ['required', 'exists:bank_accounts,id'], // Ensure the bank account exists
            'description' => ['required', 'string', 'max:255'], // Description is required and limited to 255 characters
            'transaction_type' => ['required', 'string', 'in:funds in,funds out'], // Ensure the transaction type is valid
            'document_number' => ['required', 'string', 'max:50'], // Document number is required and limited to 50 characters
            'upload' => ['nullable', 'file', 'mimes:pdf,jpg,png,doc,docx', 'max:2048'], // Optional file upload with specific formats and size limit
            'amount' => ['required', 'numeric', 'min:0.01'], // Amount is required and must be a positive number
            'payment_method' => ['required', 'string', 'max:50'], // Payment method is required and limited to 50 characters
            'reference' => ['required', 'string', 'max:255'], // Reference is required and limited to 255 characters
            'transaction_id' => ['nullable', 'string', 'max:255'], // Transaction ID is optional and limited to 255 characters
        ];
    }
    public function fails() {}


    // public function validated($key = null, $default = null)
    // {
    //     return array_merge(parent::validated(), [
    //         'created_by' => Auth::id(),
    //         'company_id' => Auth::user()->company_id,
    //     ]);
    // }
}
