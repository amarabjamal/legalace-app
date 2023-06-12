<?php

namespace App\Http\Requests;

use App\Enums\ClaimVoucherStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaimVoucherRequest extends FormRequest
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
            'status' => ClaimVoucherStatusEnum::Draft->value,
            'requester_user_id' => auth()->id(),
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
            'ticket_number' => ['required'],
            'approver_user_id' => ['required', 'exists:users,id'],
            'item_id' => ['required', 'array'],
            'item_id.*' => ['exists:disbursement_items,id'],
        ];
    }

    public function messages()
    {
        return [
            'item_id.required' => 'Item list cannot be empty.',
        ];
    }
}
