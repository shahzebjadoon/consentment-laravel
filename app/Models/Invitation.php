<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\User;



class Invitation extends Model
{
    protected $fillable = [
        'company_id',
        'inviter_id',
        'email',
        'token',
        'role',
        'expires_at'
    ];

    protected $dates = ['expires_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
}