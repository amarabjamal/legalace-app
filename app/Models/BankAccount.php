<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'label',
        'account_name',
        'bank_name',
        'account_number',
        'bank_address',
        'swift_code',
        'bank_account_type_id',
        'created_by',
        'company_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function bankAccountType() {
        return $this->belongsTo(BankAccountType::class, 'bank_account_type_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function allBankAccounts() {
        
        return $this->where([ 'company_id' => Auth::user()->company_id ])
            ->with('createdBy:id,name', 'bankAccountType:id,name')
            ->get();
    }

    public function clientAccountOptions() 
    {

        return $this->where([ 'company_id' => Auth::user()->company_id, 'bank_account_type_id' => BankAccountType::IS_CLIENT_ACCOUNT ])
            ->get(['id', 'label']);
    }
}
