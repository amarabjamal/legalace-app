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
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function caseFile() {
        return $this->belongsTo(CaseFile::class);
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class);
    }

    public function workDescriptions() {
        return $this->hasMany(workDescription::class);
    }
}
