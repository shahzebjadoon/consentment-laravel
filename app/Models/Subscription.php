<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'status',
        'started_at',
        'expired_at',
        'price_paid',
        'is_life_time',
        'coupon',
        'price_plan_id',
        'user_id',
    ];

    protected $casts = [
        'started_at'    => 'datetime',
        'expired_at'    => 'datetime',
        'is_life_time'  => 'boolean',
        'price_paid'    => 'float',
    ];

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to price plan
    public function pricePlan()
    {
        return $this->belongsTo(PricePlan::class);
    }
}
