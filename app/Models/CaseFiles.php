<?php

namespace App\Models;

use App\Models\CaseFile\DisbursementItem\DisbursementItem;
use App\Models\CaseFile\Invoices\Invoice;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CaseFiles extends Model
{
    use HasFactory;

    protected $table = 'case_files';
    protected $primaryKey = 'id';
    protected $fillable = [
        'company_id',
        'matter',
        'type',
        'file_number',
        'no_conflict_checked',
        'client_id',
        'created_by_user_id',
    ];
}
