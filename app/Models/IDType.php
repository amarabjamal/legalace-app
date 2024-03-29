<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IDType extends Model
{
    use HasFactory;
    protected $table = 'id_types';
    protected $primaryKey= 'id';
    protected $fillable = [
        'name',
        'slug'
    ];

    public const MYIC = 1;
    public const PASSPPORT = 2;

    public $timestamps = false;

    public function users() {
        $this->hasMany(User::class);
    }
}
