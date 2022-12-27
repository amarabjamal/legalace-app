<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    protected $table = 'account_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function bankAccounts() {
        return $this->hasMany(BankAccount::class);
    }

}
