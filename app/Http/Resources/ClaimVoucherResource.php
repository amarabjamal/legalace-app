<?php

namespace App\Http\Resources;

use App\Models\ClaimVoucher;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimVoucherResource extends JsonResource

{
    public static $wrap = null;
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'number' => $this->ticket_number,
            'date' => $this->submission_date ? $this->submission_date : 'N/A',
            'amount' => $this->amount->formatTo('en_MY'),
            'status' =>ClaimVoucher::STATUS[$this->status->value],
            'approver' => $this->approver->only('name'),
        ];
    }
}
