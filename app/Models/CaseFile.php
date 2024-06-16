<?php

namespace App\Models;

use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\Invoices\Invoice;
use App\Traits\HasCompanyScope;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaseFile extends Model
{
    use HasFactory, HasCompanyScope;

    protected $table = 'case_files';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'matter',
        'type',
        'file_number',
        'no_conflict_checked',
        'client_id',
        'created_by_user_id',
    ];

    protected $casts = [
        'no_conflict_checked' => 'boolean',
    ];

    public function getFormattedNameAttribute(): String
    {
        return $this->createdBy->id === auth()->id() ? $this->createdBy->name . ' (You)' : $this->createdBy->name;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function conflictReports(): HasMany
    {
        return $this->hasMany(ConflictReport::class);
    }

    public function quotation(): HasOne
    {
        return $this->hasOne(Quotation::class);
    }

    public function workDescriptions(): HasManyThrough
    {
        return $this->hasManyThrough(WorkDescription::class, Quotation::class);
    }

    public function disbursementItems(): HasMany
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoicePayments(): HasManyThrough
    {
        return $this->hasManyThrough(InvoicePayment::class, Invoice::class);
    }

    public function scopeOrderByDate($query) 
    {
        $query->orderBy('created_at', 'desc');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('matter', 'like', '%'.$search.'%')
                    ->orWhere('type', 'like', '%'.$search.'%')
                    ->orWhere('file_number', 'like', '%'.$search.'%')
                    ->orWhereHas('client', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            });
        });
    }

    public function scopeMyFiles($query) 
    {
        $query->where('created_by_user_id', '=', auth()->id())->with('client:id,name');
    }

    public function hasQuotation(): bool
    {
        return $this->quotation()->exists();
    }
}
