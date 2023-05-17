<?php

namespace App\Http\Requests;

use App\Enums\DisbursementItemFundTypeEnum;
use App\Enums\DisbursementItemStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreDisbursementItemRequest extends FormRequest
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
            'company_id' => Auth()->user()->company->id,
            'status' => DisbursementItemStatusEnum::Recorded->value,
            'case_file_id' => $this->casefile->id,
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
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'decimal:2', 'between:0.01,999999999.99'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,xlxs,xlx,docx,doc,csv,txt,png,gif,jpg,jpeg', 'max:2048'],
            'fund_type' => ['required', new Enum(DisbursementItemFundTypeEnum::class)],
            'record_type_id' => ['required', 'exists:disbursement_item_types,id'],
            'company_id' => ['required', 'exists:companies,id'],
            'status' => ['required', new Enum(DisbursementItemStatusEnum::class)],
            'case_file_id' => ['required', 'exists:case_files,id'],
        ];
    }
}
