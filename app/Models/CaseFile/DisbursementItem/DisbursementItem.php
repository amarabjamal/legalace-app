<?php

namespace App\Models\CaseFile\DisbursementItem;

use App\Casts\Money;
use App\Enums\DisbursementItemFundTypeEnum;
use App\Enums\DisbursementItemStatusEnum;
use App\Models\CaseFile;
use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementItem extends Model
{
    use HasFactory;
    use HasCompanyScope;

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
    ];

    protected $casts = [
        'amount' => Money::class . ':amount,MYR,0',
        'status' => DisbursementItemStatusEnum::class,
        'fund_type' => DisbursementItemFundTypeEnum::class,
    ];

    public function caseFile() 
    {
        return $this->belongsTo(CaseFile::class, 'case_file_id', 'id');
    }

    public function recordType() 
    {
       return $this->belongsTo(DisbursementItemType::class, 'record_type_id', 'id');
    }

    public function scopeOrderByDate($query) 
    {
        $query->orderBy('date');
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
}
