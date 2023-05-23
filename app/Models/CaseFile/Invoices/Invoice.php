<?php

namespace App\Models\CaseFile\Invoices;

use App\Casts\Money;
use App\Enums\InvoiceStatusEnum;
use App\Models\CaseFile;
use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasCompanyScope;

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
        3 => 'Paid',
        4 => 'Overdue',
        5 => 'Void',
        6 => 'Uncollectible',
    ];

    public function caseFile() 
    {
        return $this->belongsTo(CaseFile::class, 'case_file_id', 'id');
    }

    public function disbursementItems()
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function scopeFilter($query, array $filters) 
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('invoice_number', 'like', '%'.$search.'%');
            });
        });
    }

    public static function getNewInvoiceNumber() : string
    {
        $number =Invoice::where('invoice_number', 'like', 'INV-%')->where('company_id', '=', auth()->user()->company_id)->count();

        do {
            $number++;
            $invoiceNumber = 'INV-' . str_pad($number, 5, '0', STR_PAD_LEFT);
        } while(Invoice::where('invoice_number', '=', $invoiceNumber)->first() !== null);

        return $invoiceNumber;
    }
}
