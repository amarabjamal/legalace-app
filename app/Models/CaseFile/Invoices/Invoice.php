<?php

namespace App\Models\CaseFile\Invoices;

use App\Casts\Money;
use App\Enums\InvoiceStatusEnum;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\Company;
use App\Models\InvoicePayment;
use App\Models\Receipt;
use App\Models\User;
use App\Traits\HasCompanyScope;
use Brick\Math\RoundingMode;
use Brick\Money\Money as BrickMoney;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'invoices';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'invoice_number',
        'status',
        'issued_at',
        'due_at',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'grand_total',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'status' => InvoiceStatusEnum::class,
        'subtotal' => Money::class . ':subtotal,MYR,0',
        'tax_amount' => Money::class . ':tax_amount,MYR,0',
        'grand_total' => Money::class . ':grand_total,MYR,0',
    ];

    public const STATUS = [
        1 => 'Draft',
        2 => 'Open',
        3 => 'Sent',
        4 => 'Paid',
        5 => 'Overdue',
        6 => 'Void',
        7 => 'Uncollectible',
    ];


    public function getFormattedInvoiceDateAttribute()
    {
        return Carbon::parse($this->issued_at)->format('Y/m/d');
    }

    public function getFormattedDueDateAttribute()
    {
        return Carbon::parse($this->due_at)->format('Y/m/d');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('Y/m/d');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function caseFile() 
    {
        return $this->belongsTo(CaseFile::class, 'case_file_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function disbursementItems()
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function payment()
    {
        return $this->hasOne(InvoicePayment::class);
    }

    public function receipt()
    {
        return $this->hasOneThrough(Receipt::class, InvoicePayment::class);
    }

    public function scopeFilter($query, array $filters) 
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('invoice_number', 'like', '%'.$search.'%');
            });
        });
    }

    public function calculateSubtotal() 
    {
        $amount = $this->disbursementItems()->sum('amount');

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
    
    public function isEditable() : bool
    {
        return $this->status == InvoiceStatusEnum::Draft;
    }

    public function isOpen() : bool
    {
        return $this->status == InvoiceStatusEnum::Open;
    }

    public function isDeletable() : bool
    {
        return $this->status == InvoiceStatusEnum::Draft;
    }


    public static function getNextInvoiceNumber() : string
    {
        $number =Invoice::where('invoice_number', 'like', 'INV-%')->where('company_id', '=', auth()->user()->company_id)->count();

        do {
            $number++;
            $invoiceNumber = 'INV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        } while(Invoice::where('invoice_number', '=', $invoiceNumber)->first() !== null);

        return $invoiceNumber;
    }
}
