<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_histories'; // Explicitly define table name


    protected $fillable = [
        'user_id',
        'price_plan_id',
        'amount',
        'currency',
        'payment_method',
        'payment_reference',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pricePlan()
    {
        return $this->belongsTo(PricePlan::class);
    }
}
