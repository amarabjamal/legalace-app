<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WorkDescription extends Model
{
    use HasFactory;
    protected $table = 'work_descriptions';
    protected $primaryKey= 'id';
    protected $fillable = [
        'description',
        'fee',
        'quotation_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function quotation() {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    public function deleteAll($id) : bool
    {
        try {
            DB::beginTransaction();
    
            $this->where('quotation_id', $id)->delete();
    
            DB::commit();

            return true;
        } catch (Exception $e) {
    
            DB::rollback();
    
        }

        return false;
    }
}
