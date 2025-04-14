<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'configuration_id',
        'name',
        'description',
        'is_essential'
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
        return $this->hasMany(DataProcessingServices::class, 'category', 'id');
    }
}