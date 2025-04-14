<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppearanceSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'configuration_id',
        'layout_type',
        'show_deny_all',
        'more_info_type',
        'hide_language_switch',
        'background_color',
        'text_color',
        'link_color',
        'tab_color',
        'accent_color',
        'border_radius',
        'background_shadow',
        'background_overlay',
        'overlay_color',
        'overlay_opacity',
        'logo_url',
        'logo_position',
        'logo_alt_tag',
        'font_family',
        'font_size',
        'deny_button_bg',
        'deny_button_text',
        'save_button_bg',
        'save_button_text',
        'button_corner_radius',
        'active_toggle_bg',
        'active_toggle_icon',
        'inactive_toggle_bg',
        'inactive_toggle_icon',
        'disabled_toggle_bg',
        'disabled_toggle_icon',
        'privacy_button_icon',
        'privacy_button_bg',
        'privacy_button_icon_color',
        'privacy_button_desktop_size',
        'privacy_button_mobile_size',
        'custom_css_enabled',
        'custom_css'
    ];

    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}