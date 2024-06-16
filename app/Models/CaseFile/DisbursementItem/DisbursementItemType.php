<?php

namespace App\Models\CaseFile\DisbursementItem;

use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisbursementItemType extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'disbursement_item_types';
    protected $primaryKey= 'id';
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'enabled',
    ];

    public function disbursementItems() 
    {
        $this->hasMany(DisbursementItem::class);
    }

    public function scopeEnabled($query) 
    {
        $query->where('enabled', '=', 1);
    }   
}
