<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientProfile extends Model
{
    protected $table = 'client_profiles';

    protected $fillable = [
        'user_id',
        'signup_as',
        'industry_name',
        'number_of_people',
        'number_of_domain',
        'work_type',
        'visitors_pm',
        'looking_for',
        'first_hear',
    ];

 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
