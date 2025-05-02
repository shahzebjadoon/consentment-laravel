<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppearanceSettings;
use App\Models\User;
use App\Models\Configuration;


class AppearanceController extends Controller
{


    function save(Request $request)
    {
       
    //    return response()->json($request->all());
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'layoutType' => 'nullable|string',
            'showDenyAll' => 'boolean',
            'moreInfoType' => 'nullable|string',
            'hideLanguageSwitch' => 'boolean',
            'backgroundColor' => 'nullable|string',
            'textColor' => 'nullable|string',
            'linkColor' => 'nullable|string',
            'tabColor' => 'nullable|string',
            'accentColor' => 'nullable|string',
            'borderRadius' => 'nullable|string',
            'backgroundShadow' => 'boolean',
            'backgroundOverlay' => 'boolean',
            'overlayColor' => 'nullable|string',
            'overlayOpacity' => 'nullable|integer|min:0|max:100',
            'logoUrl' => 'nullable|string',
            'logoPosition' => 'nullable|string',
            'logoAltTag' => 'nullable|string',
            'fontFamily' => 'nullable|string',
            'fontSize' => 'nullable|string',
            'denyButtonBg' => 'nullable|string',
            'denyButtonText' => 'nullable|string',
            'saveButtonBg' => 'nullable|string',
            'saveButtonText' => 'nullable|string',
            'buttonCornerRadius' => 'nullable|string',
            'activeToggleBg' => 'nullable|string',
            'activeToggleIcon' => 'nullable|string',
            'inactiveToggleBg' => 'nullable|string',
            'inactiveToggleIcon' => 'nullable|string',
            'disabledToggleBg' => 'nullable|string',
            'disabledToggleIcon' => 'nullable|string',
            'privacyButtonIcon' => 'nullable|string',
            'privacyButtonBg' => 'nullable|string',
            'privacyButtonIconColor' => 'nullable|string',
            'privacyButtonDesktopSize' => 'nullable|string',
            'privacyButtonMobileSize' => 'nullable|string',
            'customCssEnabled' => 'boolean',
            'customCss' => 'nullable|string',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $company_id = $user->company_id;
        $configuration_id = Configuration::where('company_id', $company_id)->first()->id;



        $appearance = AppearanceSettings::Create(

            [
                "company_id" => $company_id,
                "configuration_id" => $configuration_id,
                'layout_type' => $validated['layoutType'] ?? null,
                'show_deny_all' => $validated['showDenyAll'],
                'more_info_type' => $validated['moreInfoType'] ?? null,
                'hide_language_switch' => $validated['hideLanguageSwitch'],
                'background_color' => $validated['backgroundColor'] ?? null,
                'text_color' => $validated['textColor'] ?? null,
                'link_color' => $validated['linkColor'] ?? null,
                'tab_color' => $validated['tabColor'] ?? null,
                'accent_color' => $validated['accentColor'] ?? null,
                'border_radius' => $validated['borderRadius'] ?? null,
                'background_shadow' => $validated['backgroundShadow'],
                'background_overlay' => $validated['backgroundOverlay'],
                'overlay_color' => $validated['overlayColor'] ?? null,
                'overlay_opacity' => $validated['overlayOpacity'] ?? null,
                'logo_url' => $validated['logoUrl'] ?? null,
                'logo_position' => $validated['logoPosition'] ?? null,
                'logo_alt_tag' => $validated['logoAltTag'] ?? null,
                'font_family' => $validated['fontFamily'] ?? null,
                'font_size' => $validated['fontSize'] ?? null,
                'deny_button_bg' => $validated['denyButtonBg'] ?? null,
                'deny_button_text' => $validated['denyButtonText'] ?? null,
                'save_button_bg' => $validated['saveButtonBg'] ?? null,
                'save_button_text' => $validated['saveButtonText'] ?? null,
                'button_corner_radius' => $validated['buttonCornerRadius'] ?? null,
                'active_toggle_bg' => $validated['activeToggleBg'] ?? null,
                'active_toggle_icon' => $validated['activeToggleIcon'] ?? null,
                'inactive_toggle_bg' => $validated['inactiveToggleBg'] ?? null,
                'inactive_toggle_icon' => $validated['inactiveToggleIcon'] ?? null,
                'disabled_toggle_bg' => $validated['disabledToggleBg'] ?? null,
                'disabled_toggle_icon' => $validated['disabledToggleIcon'] ?? null,
                'privacy_button_icon' => $validated['privacyButtonIcon'] ?? null,
                'privacy_button_bg' => $validated['privacyButtonBg'] ?? null,
                'privacy_button_icon_color' => $validated['privacyButtonIconColor'] ?? null,
                'privacy_button_desktop_size' => $validated['privacyButtonDesktopSize'] ?? null,
                'privacy_button_mobile_size' => $validated['privacyButtonMobileSize'] ?? null,
                'custom_css_enabled' => $validated['customCssEnabled'],
                'custom_css' => $validated['customCss'] ?? null,
            ]
        );
        return response()->json(['message' => 'Appearance settings saved successfully', 'data' => $appearance], 201);
    }
}
