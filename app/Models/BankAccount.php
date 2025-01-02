<?php

namespace App\Models;

use App\Casts\Money;
// use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    // use HasFactory, HasCompanyScope;
    use HasFactory;

    protected $table = 'bank_accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'label',
        'account_name',
        'bank_name',
        'account_number',
        'opening_balance',
        'bank_address',
        'swift_code',
        'bank_account_type_id',
        'created_by_user_id',
        'company_id',
    ];

    protected $casts = [
        'opening_balance' => Money::class . ':opening_balance,MYR,0',
    ];

    public function bankAccountType()
    {
        return $this->belongsTo(BankAccountType::class, 'bank_account_type_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function scopeClientAccount($query)
    {
        $query->where('bank_account_type_id', '=',  BankAccountType::IS_CLIENT_ACCOUNT);
    }

    public function scopeFirmAccount($query)
    {
        $query->where('bank_account_type_id', '=',  BankAccountType::IS_FIRM_ACCOUNT);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('label', 'like', '%' . $search . '%')
                    ->orWhere('bank_name', 'like', '%' . $search . '%')
                    ->orWhere('account_name', 'like', '%' . $search . '%')
                    ->orWhere('account_number', 'like', '%' . $search . '%');
            });
        });
    }

    public function allBankAccounts()
    {
        return $this->with('createdBy:id,name', 'bankAccountType:id,name')->get();
    }

    public function clientAccountOptions()
    {
        return $this->clientAccount()->get(['id', 'label']);
    }

    public function firmAccountOptions()
    {
        return $this->firmAccount()->get(['id', 'label']);
    }
}
