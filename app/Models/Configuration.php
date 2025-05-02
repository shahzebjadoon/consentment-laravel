<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
        'data_controller',
        'hash_key'
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function encodeId($id) {
        return rtrim(strtr(base64_encode(pack('N', $id)), '+/', '-_'), '=');
    }

   protected static function decodeId($encoded) {
        $decoded = unpack('N', base64_decode(strtr($encoded, '-_', '+/')));
        return $decoded[1] ?? null;
    }
    
    

    protected static function booted()
    {
        static::created(function ($configuration) {
            // Only set if not already set
            if (empty($configuration->hash_key)) {
            
                $configuration->updateQuietly([
                    'hash_key' => self::encodeId($configuration->id)
                ]);
            }
        });
    }
  
    }
    