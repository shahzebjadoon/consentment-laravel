<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'legal_name',
        'street',
        'city',
        'zip_code',
        'country',
        'billing_account',
        'vat_id',
        'website',
        'industry',
        'contact_email',
        'contact_phone',
        'subscription_plan',
        'subscription_status',
        'subscription_expires_at',
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}