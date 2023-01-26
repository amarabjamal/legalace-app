<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'quotations';
    protected $primaryKey= 'id';
    protected $fillable = [
        'deposit_amount',
        'is_paid',
        'payment_date',
        'issued_by',
        'case_file_id',
        'bank_account_id',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function caseFile() {
        return $this->belongsTo(CaseFile::class);
    }

    public function Deposit() {
        return $this->has(Deposit::class);
    }
}
