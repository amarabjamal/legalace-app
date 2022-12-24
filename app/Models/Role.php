<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $primaryKey='id';
    protected $fillable = [
        'name'
    ];
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
