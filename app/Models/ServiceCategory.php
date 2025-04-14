<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories';

    protected $fillable = [
        'company_id',
        'configuration_id',
        'name',
        'description',
        'identifier',
        'is_essential',
        'is_default',
        'order_index'
    ];

    protected $casts = [
        'is_essential' => 'boolean',
        'is_default' => 'boolean'
    ];

    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function services()
    {
        return $this->hasMany(DataProcessingServices::class, 'category', 'identifier');
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class, 'category_id');
    }
}