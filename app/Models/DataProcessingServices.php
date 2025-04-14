<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataProcessingServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'configuration_id',
        'name',
        'template_id',
        'category',
        'status',
        'is_essential',
        'data_sharing_eu',
        'accepted_by_default'
    ];

    protected $casts = [
        'is_essential' => 'boolean',
        'data_sharing_eu' => 'boolean',
        'accepted_by_default' => 'boolean'
    ];

    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function category()
{
    return $this->belongsTo('App\Models\ServiceCategory', 'category', 'identifier');
}
}