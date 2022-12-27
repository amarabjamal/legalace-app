<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCaseFile extends Model
{
    use HasFactory;
    protected $table = 'user_case_file';
    protected $primaryKey= 'id';
    protected $fillable = [
        'user_id',
        'case_file_id'
    ];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function caseFile() {
        return $this->belongsTo(CaseFile::class);
    }
}
