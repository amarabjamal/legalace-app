<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 
        'id_types_id',
        'id_num',
        'email', 
        'phone_number',
        'address',
        'timestamps',
        'created_by'
    ];

    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function caseFiles() {
        return $this->hasMany(CaseFile::class);
    }
}
