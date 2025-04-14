<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'company_id',
        'name',
        'framework_type',
        'framework_region',
        'domain',
        'status',
        'data_controller'
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}