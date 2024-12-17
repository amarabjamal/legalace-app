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
        'id',
        'date',
        'description',
        'transaction_type',
        'payment_method',
        'document_number',
        'upload',
        'debit',
        'credit',
        'balance',
        'bank_account_id',
        'reference',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public const UPLOAD_PATH = 'files/case-files/document-upload/';

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // public function company() {
    //     return $this->belongsTo(Company::class, 'company_id', 'id');
    // }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }
}
