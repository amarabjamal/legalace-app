<?php

namespace App\Models\CaseFile\DisbursementItem;

use App\Traits\HasCompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementItemType extends Model
{
    use HasFactory;
    use HasCompanyScope;

    protected $table = 'disbursement_item_types';
    protected $primaryKey= 'id';
    protected $fillable = [
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
