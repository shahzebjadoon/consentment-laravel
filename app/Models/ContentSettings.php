<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSettings extends Model
{
    use HasFactory;
    
    protected $table = 'content_settings';
    
    protected $fillable = [
        'company_id',
        'configuration_id',
        // First Layer fields
        'first_layer_title',
        'first_layer_message',
        'mobile_specific_message',
        'mobile_message',
        'legal_notice_text',
        'legal_notice_url',
        'privacy_policy_text',
        'privacy_policy_url',
        // Second Layer fields
        'second_layer_title',
        'second_layer_description',
        'services_title',
        'services_description',
        'categories_title',
        'categories_description',
        'accept_all_button',
        'deny_all_button',
        'save_button',
        // Labels fields
'accept_button_label',
'deny_button_label',
'more_info_label',
'service_provider_label',
'privacy_policy_label',
'legitimate_interest_label',
'storage_info_label',
'save_settings_label',
'accept_selected_label',
'essential_category_label',
'marketing_category_label',
'functional_category_label',
'analytics_category_label',
'active_status_label',
'inactive_status_label',
'required_status_label'
    ];
    
    protected $casts = [
        'mobile_specific_message' => 'boolean'
    ];
    
    /**
     * Get the company that owns the content settings.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the configuration that owns the content settings.
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }
}