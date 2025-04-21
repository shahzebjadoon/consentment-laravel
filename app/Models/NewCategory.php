<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryTranslation;
use App\Models\DpsScan;

class NewCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_essential' => 'boolean',
        'is_default' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Get the company that owns the category.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the configuration that owns the category.
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    /**
     * Get all DPS scans for this category with valid display_name
     */
    public function dpsScans()
    {
        return DpsScan::select(['id', 'display_name'])
            ->where('configuration_id', $this->configuration_id)
            ->where('category', $this->identifier)
            ->where('status', 'added')
            ->whereNotNull('display_name')
            ->whereRaw("TRIM(display_name) != ''") // Ensure display_name is not empty string
            ->orderBy('display_name');
    }

    /**
     * Get all translations for this category.
     */
    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class, 'category_id');
    }

    /**
     * Get translated value for a specific field and language.
     *
     * @param string $field
     * @param string $language
     * @return string|null
     */
    public function getTranslation($field, $language)
    {
        $translation = $this->translations()
            ->where('field', $field)
            ->where('language', $language)
            ->first();

        return $translation ? $translation->translation : null;
    }
}