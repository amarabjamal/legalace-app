<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey= 'id';
    protected $fillable = [
        'name',
        'slug'
    ];

    public $timestamps = false;

    public const IS_ADMIN = 1;
    public const IS_LAWYER = 2;

    // public function userRoles() {
    //     return $this->hasMany(UserRole::class);
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
