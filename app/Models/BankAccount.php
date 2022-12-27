<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'account_name',
        'bank_name',
        'account_number',
        'bank_address',
        'swift_code',
        'account_type',
        'label',
        'created_by',
        'company_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function accountType() {
        return $this->belongsTo(AccountType::class, 'account_type', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
