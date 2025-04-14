<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpsScan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dps_scans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'configuration_id',
        'domain',
        'service_name',
        'service_url',
        'source_domain',
        'category',
        'status',
        'scan_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scan_date' => 'datetime',
    ];

    /**
     * Get the company that owns the scan.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the configuration that owns the scan.
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }
}