<?php

namespace App\Http\Requests;

use App\Enums\InvoiceStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreInvoiceRequest extends FormRequest
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
            'status' => InvoiceStatusEnum::Draft->value,
            'created_by' => auth()->id(),
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
            'invoice_number' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'date', 'after_or_equal:today'],
            'due_at' => ['required', 'date', 'after:issued_at'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items_id' => ['nullable', 'array'],
            'items_id.*' => ['exists:disbursement_items,id'],
            'company_id' => ['required', 'exists:companies,id'],
            'status' => ['required', new Enum(InvoiceStatusEnum::class)],
            'created_by' => ['required', 'exists:users,id'],
        ];
    }
}
