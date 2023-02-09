<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 
        'id_type_id',
        'id_number',
        'email', 
        'phone_number',
        'address',
        'timestamps',
        'created_by'
    ];

    use HasFactory;

    public function idType() {
        return $this->belongsTo(IDType::class, 'id_type_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function caseFiles() {
        return $this->hasMany(CaseFile::class);
    }
}
