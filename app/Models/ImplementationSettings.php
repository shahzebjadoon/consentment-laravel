<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImplementationSettings extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'implementation_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'configuration_id',
        'blocking_mode',
        'current_version',
        'scan_frequency',
        'auto_populate',
        'include_trackers',
        'last_scan_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'auto_populate' => 'boolean',
        'include_trackers' => 'boolean',
        'last_scan_date' => 'datetime',
    ];

    /**
     * Get the company that owns the implementation settings.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the configuration that owns the implementation settings.
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }
}