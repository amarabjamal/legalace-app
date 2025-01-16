<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOperationalCostRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'details' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:50'],
            'recurring_period' => ['nullable', 'string', 'max:50'],
            'first_payment_date' => ['nullable', 'date'],
            'no_of_payment' => ['nullable', 'numeric', 'min:1'],
            'upload' => ['nullable', 'file', 'mimes:pdf,jpg,png,doc,docx', 'max:2048'],
            'document_number' => ['required', 'string', 'max:50'],
        ];
    }
    public function fails() {}
}
