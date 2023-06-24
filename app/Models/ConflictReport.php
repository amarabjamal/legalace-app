<?php

namespace App\Models;

use App\Traits\HasCompanyScope;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConflictReport extends Model
{
    use HasFactory, HasCompanyScope, SoftDeletes;

    protected $table = 'conflict_reports';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'case_file_id',
        'created_by_user_id',
        'content',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function getRelativeCreatedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
