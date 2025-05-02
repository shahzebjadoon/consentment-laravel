<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   
    
    protected $fillable = [
        'type', 'name', 'email', 'password', 'timezone', 'active', 
        'company_id', 'role', 'price_plan_id', 'agency_id', 'reset_otp', 'otp_expires_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the companies that the user belongs to.
     */
    public function companies()
{
    return $this->belongsToMany(
        \App\Models\Company::class, 
        'company_user', 
        'user_id', 
        'company_id'
    )->withPivot('role')->withTimestamps();
}

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if the user is an admin for a specific company.
     */
    public function isCompanyAdmin($companyId)
    {
        return $this->companies()
            ->wherePivot('company_id', $companyId)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}