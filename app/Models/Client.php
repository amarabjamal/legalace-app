<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
        "name",
        "email",
        "id_type_id",
        "id_number",
        "address",
        "phone_number",
        "company_name", //
        "company_address",    //
        "outstanding_balance",
        "created_by",
    ];

    use HasFactory;

    public function idType()
    {
        return $this->belongsTo(IDType::class, 'id_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function caseFiles()
    {
        return $this->hasMany(CaseFile::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
}
