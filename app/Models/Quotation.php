<?php

namespace App\Models;

use App\Casts\Money;
use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory, HasCompanyScope;
    
    protected $table = 'quotations';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'deposit_amount',
        'is_paid',
        'payment_date',
        'created_by_user_id',
        'case_file_id',
        'bank_account_id',
    ];

    protected $casts = [
        'deposit_amount' => Money::class . ':deposit_amount,MYR,0',
    ];
    
    public function workDescriptions() {
        return $this->hasMany(workDescription::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function caseFile() {
        return $this->belongsTo(CaseFile::class, 'case_file_id', 'id');
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
}
