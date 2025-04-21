<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentHistory extends Model
{
    protected $table = 'consent_history';
    
    protected $fillable = [
        'company_id',
        'configuration_id',
        'user_id',
        'ip_address',
        'user_agent',
        'consent_data'
    ];
    
    protected $casts = [
        'consent_data' => 'array'
    ];
    
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}