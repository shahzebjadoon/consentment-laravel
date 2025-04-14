<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpsLibrary extends Model
{
    use HasFactory;

    protected $table = 'dps_library';

    protected $fillable = [
        'name',
        'domain_pattern',
        'category',
        'description',
        'provider_name',
        'provider_url',
        'privacy_policy_url',
        'logo_url',
        'script_patterns',
        'cookie_patterns',
        'data_collected',
        'data_retention',
        'data_sharing',
        'is_official'
    ];

    protected $casts = [
        'data_sharing' => 'boolean',
        'is_official' => 'boolean',
    ];
}