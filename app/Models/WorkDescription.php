<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkDescription extends Model
{
    use HasFactory;
    protected $table = 'work_descriptions';
    protected $primaryKey= 'id';
    protected $fillable = [
        'description',
        'fee',
        'quotation_id',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function createdBy() {
        return $this->belongsTo(User::class);
    }

    public function quotation() {
        return $this->belongsTo(Quotation::class);
    }
}
