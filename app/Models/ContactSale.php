<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class ContactSale extends Model
{
    use HasFactory;

    protected $table = 'contact_sales';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'company_name',
        'searching_for',
        'message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
