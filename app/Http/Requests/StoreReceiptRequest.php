<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReceiptRequest extends FormRequest
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
            'receipt_number' => ['required', 'string', 'max:255', Rule::unique('receipts', 'receipt_number')->where(fn(Builder $query) => $query->where('company_id', auth()->user()->company->id))],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
