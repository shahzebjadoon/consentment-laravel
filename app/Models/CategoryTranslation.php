<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'category_translations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'language',
        'field',
        'translation'
    ];

    /**
     * Get the category that owns the translation.
     */
    public function category()
    {
        return $this->belongsTo(NewCategory::class, 'category_id');
    }
}