<?php

namespace App\Models;

use App\Casts\Money;
use App\Traits\HasCompanyScope;
use Brick\Math\RoundingMode;
use Brick\Money\Money as BrickMoney;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quotation extends Model
{
    use HasFactory, HasCompanyScope;
    
    protected $table = 'quotations';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'deposit_amount',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'created_by_user_id',
        'case_file_id',
        'bank_account_id',
        'sent_at',
    ];

    protected $casts = [
        'deposit_amount' => Money::class . ':deposit_amount,MYR,0',
        'subtotal' => Money::class . ':subtotal,MYR,0',
        'tax_amount' => Money::class . ':tax_amount,MYR,0',
        'total' => Money::class . ':total,MYR,0',
    ];
    
    public function workDescriptions() {
        return $this->hasMany(workDescription::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function caseFile() {
        return $this->belongsTo(CaseFile::class, 'case_file_id', 'id');
    }

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }

    public function payment() : HasOne
    {
        return $this->hasOne(QuotationPayment::class);
    }

    public function calculateSubtotal() 
    {
        $amount = $this->workDescriptions()->sum('fee');

        return BrickMoney::of($amount, 'MYR');
    }

    public function calculateTaxRate() {
        return 0;
    }

    public function calculateTax() 
    {
        $taxRate = $this->calculateTaxRate();

        return $this->calculateSubtotal()->multipliedBy($taxRate, RoundingMode::UP);
    }

    public function calculateTotal() 
    {
        $subtotal = $this->calculateSubtotal();
        $tax = $this->calculateTax();

        return  $subtotal->plus($tax);
    }

    public function isPaid() : bool
    {
        return $this->payment()->exists();
    }
}
