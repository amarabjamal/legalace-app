<?php

namespace App\Http\Requests;

use App\Enums\DisbursementItemFundTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDisbursementItemRequest extends FormRequest
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
            'date' => ['required', 'date', 'before_or_equal:today'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'decimal:2', 'between:0.01,999999999.99'],
            'receipt' => ['nullable', 'file', 'mimes:pdf,xlxs,xlx,docx,doc,csv,txt,png,gif,jpg,jpeg', 'max:2048'],
            'fund_type' => ['required', new Enum(DisbursementItemFundTypeEnum::class)],
            'record_type_id' => ['required', 'exists:disbursement_item_types,id'],
        ];
    }
}
