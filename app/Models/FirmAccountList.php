<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmAccountList extends Model
{
    use HasFactory;
    protected $table = 'bank_accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'label',
        'account_name',
        'bank_name',
        'account_number',
        'opening_balance',
        'swift_code',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
    public function firmAccounts()
    {
        return $this->hasMany(FirmAccount::class, 'bank_account_id', 'id');
    }
    public function totalDebit()
    {
        return $this->firmAccounts()->sum('debit');
    }

    public function totalCredit()
    {
        return $this->firmAccounts()->sum('credit');
    }
}
