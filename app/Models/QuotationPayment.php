<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\PaymentMethodEnum;
use App\Traits\HasCompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\Cast\String_;

class QuotationPayment extends Model
{
    use HasFactory,HasCompanyScope, SoftDeletes;

    protected $table = 'quotation_payments';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'quotation_id',
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

    protected $dates = [
        'date',
    ];

    public const RECEIPT_PATH = 'files/case-files/quotation-payment-receipts/';

    public const PAYMENT_METHODS = [
        1 => 'Instant Transfer',
        2 => 'Interbank GIRO Transfer',
        3 => 'Cheque',
    ];

    public function getPaymentMethodDisplayTextAttribute(): String
    {
        return QuotationPayment::PAYMENT_METHODS[$this->payment_method_code->value];
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
