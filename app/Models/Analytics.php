<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'browser',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'displays' => 'integer',
        'interactions' => 'integer',
        'ignores' => 'integer',
        'accept_all' => 'integer',
        'deny_all' => 'integer',
        'custom_choice' => 'integer',
    ];

    /**
     * Get the company that owns the analytics entry.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the configuration that owns the analytics entry.
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    /**
     * Calculate interaction rate
     * 
     * @return float
     */
    public function getInteractionRateAttribute()
    {
        if ($this->displays <= 0) {
            return 0;
        }
        
        return round(($this->interactions / $this->displays) * 100, 2);
    }

    /**
     * Calculate accept rate
     * 
     * @return float
     */
    public function getAcceptRateAttribute()
    {
        if ($this->interactions <= 0) {
            return 0;
        }
        
        return round(($this->accept_all / $this->interactions) * 100, 2);
    }
}