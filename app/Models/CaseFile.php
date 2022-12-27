<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseFile extends Model
{
    use HasFactory;
    protected $table = 'case_files';
    protected $primaryKey= 'id';
    protected $fillable = [
        'matter',
        'type',
        'file_no',
        'no_conflict_checked',
        'client_id',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function createdBy() {
        return $this->belongsTo(User::class);
    }
}
