<?php

namespace App\Models;

use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'receipts';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'invoice_payment_id',
        'receipt_number',
        'notes',
        'is_sent',
        'created_by_user_id',
    ];

    public function payment() 
    {
        return $this->belongsTo(InvoicePayment::class, 'invoice_payment_id', 'id');
    }

    public function getClientAttribute()
    {
        return $this->payment->caseFile->client;
    }

    public static function getNextReceiptNumber() : string
    {
        $number = Receipt::where('receipt_number', 'like', 'RCP-%')->count();

        do {
            $number++;
            $receiptNumber = 'RCP-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        } while(Receipt::where('receipt_number', '=', $receiptNumber)->first() !== null);

        return $receiptNumber;
    }
}
