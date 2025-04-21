<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsentAnalytics extends Model
{
    protected $table = 'analytics';
    
    protected $fillable = [
        'company_id',
        'configuration_id',
        'date',
        'displays',
        'interactions',
        'ignores',
        'accept_all',
        'deny_all',
        'custom_choice',
        'country',
        'device_type',
        'os',
        'browser'
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