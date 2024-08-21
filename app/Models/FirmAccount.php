<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmAccount extends Model
{
    use HasFactory;
    protected $table = 'firm_account';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'description',
        'transaction_type',
        'debit',
        'credit',
        'balance',
        // 'company_id',
        'bank_account_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // public function company() {
    //     return $this->belongsTo(Company::class, 'company_id', 'id');
    // }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }

    
}
