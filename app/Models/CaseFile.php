<?php

namespace App\Models;

use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\Invoices\Invoice;
use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CaseFile extends Model
{
    use HasFactory;

    protected $table = 'case_files';
    protected $primaryKey= 'id';
    protected $fillable = [
        'matter',
        'type',
        'file_number',
        'no_conflict_checked',
        'client_id',
        'created_by',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client() 
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function createdBy() 
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function workDescriptions() 
    {
        return $this->hasManyThrough(WorkDescription::class, Quotation::class);
    }

    public function disbursementItems() 
    {
        return $this->hasMany(DisbursementItem::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
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
        $query->where('created_by', '=', auth()->id())->with('client:id,name');
    }

    public function myCaseFile() 
    {

        return $this->where('created_by', '=', Auth::id())
            ->with('client:id,name')
            ->get();
    }

    public function getCaseFileById($id)  
    {
        
        return $this->where('id', '=', $id)
            ->with('client:id,name', 'createdBy:id,name')
            ->first();
    }

    public function getCaseFileByIdWithAddress($id)  
    {
        
        return $this->where('id', '=', $id)
            ->with('client:id,name,address', 'createdBy:id,name')
            ->first();
    }
}
