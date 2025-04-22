<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Framework extends Model
{
    protected $fillable = [
        'name', 'code', 'region', 'logo', 'description', 'legal_url', 
        'is_require_prior_concent', 'is_require_withdrawal_option',
        'is_legal_require_basis', 'is_cookies_categoray_require',
        'consent_expiry_days', 'show_accept_all', 'show_reject_all',
        'show_settings_link', 'default_consent_state'
    ];
}