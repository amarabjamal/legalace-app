<?php

namespace App\Models\CaseFile\DisbursementItem;

use App\Casts\Money;
use App\Enums\DisbursementItemFundTypeEnum;
use App\Enums\DisbursementItemStatusEnum;
use App\Models\CaseFile;
use App\Models\CaseFile\Invoices\Invoice;
use App\Models\User;
use App\Traits\HasCompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisbursementItem extends Model
{
    use HasFactory, HasCompanyScope;

    protected $table = 'disbursement_items';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'date',
        'name',
        'description',
        'amount',
        'receipt',
        'status',
        'fund_type',
        'case_file_id',
        'record_type_id',
        'created_by_user_id',
    ];

    protected $casts = [
        'amount' => Money::class . ':amount,MYR,0',
        'status' => DisbursementItemStatusEnum::class,
        'fund_type' => DisbursementItemFundTypeEnum::class,
    ];

    public const FUND_TYPE = [
        1 => 'Paid by Lawyer',
        2 => 'Firm Account',
        3 => 'Petty Cash',
    ];

    public const STATUS = [
        1 => 'Recorded',
        2 => 'Drafted',
        3 => 'Invoiced',
        4 => 'Paid',
        5 => 'Claiming',
        6 => 'Pending Approval',
        7 => 'Approved',
        8 => 'Disbursed',
        9 => 'Denied',
    ];

    public const RECEIPT_PATH = 'case-files/disbursement-items/receipts/';

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    public function caseFile() 
    {
        return $this->belongsTo(CaseFile::class, 'case_file_id', 'id');
    }

    public function recordType() 
    {
       return $this->belongsTo(DisbursementItemType::class, 'record_type_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function scopeOrderByDate($query) 
    {
        $query->orderBy('date', 'desc');
    }

    public function scopeFilter($query, array $filters) 
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        });
    }

    public function scopeRecorded($query)
    {
        $query->where('status', '=', DisbursementItemStatusEnum::Recorded->value);
    }

    public function scopePaidByLawyer($query)
    {
        $query->where('fund_type', '=', DisbursementItemFundTypeEnum::PaidByLawyer->value)
            ->where('status', '=', DisbursementItemStatusEnum::PaidByClient->value);   
    }

    public function scopeFromInvoiceCreatedByCurrentUser($query)
    {
        $query->whereHas('caseFile', function ($query) {
            $query->where('created_by_user_id', '=', auth()->id());
        });
    }

    public function isDeletable() : bool 
    {
        return $this->status == DisbursementItemStatusEnum::Recorded;
    }


}
