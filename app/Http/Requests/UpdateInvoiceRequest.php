<?php

namespace App\Http\Requests;

use App\Models\CaseFile\Invoices\Invoice;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
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
            'invoice_number' => ['required', 'string', 'max:255', Rule::unique('invoices', 'invoice_number')->ignore($this->invoice->id)->where(fn(Builder $query) => $query->where('company_id', auth()->user()->company->id))],
            'issued_at' => ['required', 'date', function ($attribute, $value, $fail) {
                if(Invoice::where('id', $this->invoice->id)->first()->field >= $value)
                    $fail('Invoice date must be greater than/equal previous record');
            }],
            'due_at' => ['required', 'date', 'after:issued_at'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items_id' => ['nullable', 'array'],
            'items_id.*' => ['exists:disbursement_items,id'],
        ];
    }
}
