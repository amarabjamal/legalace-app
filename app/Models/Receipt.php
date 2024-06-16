<?php

namespace App\Models;

use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\Cast\String_;

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
        'sent_at'
    ];

    protected $dates = [
        'sent_at',
    ];

    public function getFormattedDateAttribute(): String 
    {
        return $this->sent_at ? $this->sent_at->format('d F Y, h:i A'): 'N/A';
    }

    public function payment() 
    {
        return $this->belongsTo(InvoicePayment::class, 'invoice_payment_id', 'id');
    }

    public function createdBy() 
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
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
