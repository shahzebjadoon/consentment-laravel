<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    use HasFactory;

    protected $table = 'company_user';
    protected $fillable = [
        'company_id',
        'user_id',
        'role',
    ];
    public $timestamps = true;
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isMember()
    {
        return $this->role === 'member';
    }
    public function isOwner()
    {
        return $this->role === 'owner';
    }
}
