<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CaseFile extends Model
{
    use HasFactory;
    protected $table = 'case_files';
    protected $primaryKey= 'id';
    protected $fillable = [
        'matter',
        'type',
        'file_number',
        'no_conflict_checked',
        'client_id',
        'created_by',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy() 
    {
        return $this->belongsTo(User::class);
    }

    public function myCaseFile() 
    {

        return $this->where('created_by', '=', Auth::id())
            ->with('client:id,name')
            ->get();
    }
}
