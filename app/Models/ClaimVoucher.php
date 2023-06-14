<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\ClaimVoucherStatusEnum;
use App\Http\Resources\ClaimVoucherResource;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Traits\HasCompanyScope;
use Brick\Money\Money as BrickMoney;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimVoucher extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'claim_vouchers';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'ticket_number',
        'submit_date',
        'amount',
        'status',
        'bank_name',
        'bank_account_number',
        'bank_account_holder_name',
        'approver_user_id',
        'requester_user_id',
    ];

    protected $casts = [
        'status' => ClaimVoucherStatusEnum::class,
        'amount' => Money::class . ':amount,MYR,0',
    ];

    public const STATUS = [
        1 => 'Draft',
        2 => 'Submitted',
        3 => 'Approved',
        4 => 'Rejected',
        5 => 'Reimbursed',
    ];

    public function items() : HasMany
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function requester() : BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_user_id', 'id');
    }

    public function approver() : BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_user_id', 'id');
    }

    public function toResource() : ClaimVoucherResource
    {
        return new ClaimVoucherResource($this);
    }

    public function scopeFilter($query, array $filters) 
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('ticket_number', 'like', '%'.$search.'%');
            });
        });
    }

    public function scopePending($query)
    {
        $query->where('status', '=', ClaimVoucherStatusEnum::Submitted->value);
    }

    public function calculateTotal() : BrickMoney
    {
        $amount = $this->items()->sum('amount');

        return BrickMoney::of($amount, 'MYR');
    }

    public static function getNextTicketNumber() : string
    {
        $number = ClaimVoucher::where('ticket_number', 'like', 'RT-%')->count();

        do {
            $number++;
            $ticketNumber = 'RT-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        } while(ClaimVoucher::where('ticket_number', '=', $ticketNumber)->first() !== null);

        return $ticketNumber;
    }
}
