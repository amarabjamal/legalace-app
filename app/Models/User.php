<?php

namespace App\Models;

use App\Traits\HasCompanyScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasCompanyScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'id_type_id',
        'id_number',
        'employee_id',
        'contact_number',
        'birthdate',
        'profile_image_url',
        'is_active',
        'access_expiry_date',
        'company_id',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getRoleListAttribute()
    {
        $roles = '';

        if($this->roles->isNotEmpty()) {
            foreach($this->roles as $key => $role) {
                $roles .= $role['name'];
                if($key != count($this->roles) - 1) {
                    $roles .= ', ';
                }
            }
        } else {
            $roles = 'Unsigned';
        }

        return $roles;
    }

    public function userRoles() 
    {
        return $this->hasMany(UserRole::class);
    }

    public function roles() 
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function idType() 
    {
        return $this->belongsTo(IDType::class);
    }

    public function company() 
    {
        return $this->belongsTo(Company::class);
    }

    public function clients() 
    {
        return $this->hasMany(Client::class);
    }

    public function caseFiles() 
    {
        return $this->hasMany(CaseFile::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('id_number', 'like', '%'.$search.'%')
                    ->orWhere('employee_id', 'like', '%'.$search.'%');
            });
        });     
    }

    public function scopeAdmin($query)
    {
        $query->whereHas('roles', function ($query) {
            $query->where('slug', '=', 'admin');
        });
    }
    
    public function scopeLawyer($query)
    {
        $query->whereHas('roles', function ($query) {
            $query->where('slug', '=', 'lawyer');
        });
    }

    public function scopeNotCurrentUser($query)
    {
        $query->where('id', '!=', auth()->id());
    }

    public function hasRole($roleName): bool
    {
        return $this->roles->contains('slug', $roleName);
    }
}
