<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\PaymentMethodEnum;
use App\Models\CaseFile\Invoices\Invoice;
use App\Traits\HasCompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'invoice_payments';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'invoice_id',
        'date',
        'client_bank_account_id',
        'payment_method_code',
        'receipt_filename',
        'description',
        'amount',
        'created_by_user_id',
        'is_synced',
        'client_account_id',
    ];

    protected $casts = [
        'payment_method_code' => PaymentMethodEnum::class,
        'amount' => Money::class . ':amount,MYR,0',
    ];

    public const RECEIPT_PATH = 'files/case-files/invoice-payment-receipts/';

    public const PAYMENT_METHODS = [
        1 => 'Instant Transfer',
        2 => 'Interbank GIRO Transfer',
        3 => 'Cheque',
    ];

    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->format('Y/m/d');
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function caseFile()
    {
        return $this->hasOneThrough(CaseFile::class, Invoice::class);
    }

    public function clientBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'client_bank_account_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }
}
