<?php

namespace App\Models\CaseFile\DisbursementItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisbursementItemType extends Model
{
    use HasFactory;

    protected $table = 'disbursement_item_types';
    protected $primaryKey= 'id';
    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    public function disbursementItems() {
        $this->hasMany(DisbursementItem::class);
    }
}
