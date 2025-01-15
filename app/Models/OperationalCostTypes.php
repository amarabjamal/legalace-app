<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalCostTypes extends Model
{
    use HasFactory;

    protected $table = 'operational_cost_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];
}
