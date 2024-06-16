<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'company_id' => auth()->user()->company_id,
            'created_by_user_id' => auth()->id(),
            'case_file_id' => $this->case_file->id,
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
            'case_file_id' => ['required', Rule::exists('case_files', 'id')->where(function ($query) {
                $query->where('no_conflict_checked', true);
            })],
            'work_descriptions' => ['required', 'array'],
            'work_descriptions.*.description' => ['required', 'string'],
            'work_descriptions.*.fee' => ['required', 'numeric', 'decimal:2', 'between:0.01,999999999.99'],
            'deposit_amount' => ['required', 'numeric', 'decimal:2', 'between:0.01,999999999.99'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id']
        ];
    }

    public function messages()
    {
        return [
            'case_file_id.exists' => 'The case file must be declared as no conflict before proceed.',
            'work_descriptions.required' => 'The item list cannot be empty.',
            'work_descriptions.*.description.required' => 'The work desc :position cannot be empty.',
            'work_descriptions.*.fee.between' => 'The work fee :position must be between 0.01 and 999999999.99.',
        ];
    }
}
