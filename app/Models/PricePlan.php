<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PricePlan extends Model
{
    protected $table = 'prices_plans'; // Explicitly define table name

    protected $fillable = [
        'membership',
        'domain_allowed',
        'level',
        'price_month',
        'price_yearly',
        'max_domain',
        'is_custom',
        'max_visitors',
        'max_languages',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'price_month' => 'float',
        'price_yearly' => 'float',
        'max_domain' => 'integer',
        'max_visitors' => 'integer',
        'max_languages' => 'integer',
    ];
}
