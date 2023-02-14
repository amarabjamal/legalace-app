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
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function createdBy() 
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function quotation()
    {
        return $this->has(Quotation::class);
    }

    public function myCaseFile() 
    {

        return $this->where('created_by', '=', Auth::id())
            ->with('client:id,name')
            ->get();
    }

    public function getCaseFileById($id)  
    {
        
        return $this->where('id', '=', $id)
            ->with('client:id,name', 'createdBy:id,name')
            ->first();
    }

    public function getCaseFileByIdWithAddress($id)  
    {
        
        return $this->where('id', '=', $id)
            ->with('client:id,name,address', 'createdBy:id,name')
            ->first();
    }
}
