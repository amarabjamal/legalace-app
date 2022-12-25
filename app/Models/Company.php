<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $attributes = [
        'name' => "Default",
        'reg_no' => "Default",
        'address' => "Default",
        'email' => "Default",
        'website' => "Default",
    ];

    protected $fillable = [
        'name',
        'reg_no',
        'address',
        'email',
        'website',
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
