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

    public const IS_ADMIN = 1;
    public const IS_LAWYER = 2;

    public function userRoles() {
        return $this->hasMany(UserRole::class);
    }

    // have to be fix
    public $timestamp = false;

    public function setUpdatedAt($value) {
        
    }
    
    public function getUpdatedAt() {

    }
    public function setCreatedAt($value) {
        
    }
    
    public function getCreatedAt() {

    }
}
