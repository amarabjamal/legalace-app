<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccount extends Model
{
    use HasFactory;
    protected $table = 'client_accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'description',
        'transaction_type',
        'payment_method',
        'debit',
        'credit',
        'balance',
        'created_by',
        'company_id',
        'bank_account_id',
        'document_number',
        'upload',
        'reference',
        'transaction_id',
        'created_at',
        'updated_at',
    ];

    public const UPLOAD_PATH = 'files/case-files/document-upload/';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
