<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountReporting extends Model
{
    use HasFactory;
    protected $table = 'account_reportings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'description',
        'client',
        'debit',
        'credit',
        'balance',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
