<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccountType extends Model
{
    use HasFactory;

    protected $table = 'bank_account_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug',
    ];

    public $timestamps = false;

    public const IS_CLIENT_ACCOUNT = 1;
    public const IS_FIRM_ACCOUNT = 2;

    public function bankAccounts() {
        return $this->hasMany(BankAccount::class);
    }

}
