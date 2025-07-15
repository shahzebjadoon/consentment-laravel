<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Domains\Announcement\Models{
/**
 * Class Announcement.
 *
 * @property int $id
 * @property string|null $area
 * @property string $type
 * @property string $message
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement enabled()
 * @method static \Database\Factories\AnnouncementFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement forArea($area)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement inTimeFrame()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 */
	class Announcement extends \Eloquent {}
}

namespace App\Domains\Auth\Models{
/**
 * Class PasswordHistory.
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordHistory whereUpdatedAt($value)
 */
	class PasswordHistory extends \Eloquent {}
}

namespace App\Domains\Auth\Models{
/**
 * Class Permission.
 *
 * @property int $id
 * @property string $type
 * @property string $guard_name
 * @property string $name
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $children
 * @property-read int|null $children_count
 * @property-read Permission|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission isChild()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission isMaster()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission isParent()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission singular()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Domains\Auth\Models{
/**
 * Class Role.
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $permissions_label
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role search($term)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Domains\Auth\Models{
/**
 * Class User.
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $password_changed_at
 * @property bool $active
 * @property string|null $timezone
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $last_login_ip
 * @property bool $to_be_logged_out
 * @property string|null $provider
 * @property string|null $provider_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $company_id
 * @property string|null $role
 * @property int|null $price_plan_id
 * @property int|null $agency_id
 * @property string|null $reset_otp
 * @property string|null $otp_expires_at
 * @property-read mixed $avatar
 * @property-read string $permissions_label
 * @property-read string $roles_label
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\PasswordHistory[] $passwordHistories
 * @property-read int|null $password_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Domains\Auth\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \DarkGhostHunter\Laraguard\Eloquent\TwoFactorAuthentication $twoFactorAuth
 * @method static \Illuminate\Database\Eloquent\Builder|User admins()
 * @method static \Illuminate\Database\Eloquent\Builder|User allAccess()
 * @method static \Illuminate\Database\Eloquent\Builder|User byType($type)
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyActive()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyDeactivated()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User search($term)
 * @method static \Illuminate\Database\Eloquent\Builder|User users()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePricePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereResetOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereToBeLoggedOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail, \DarkGhostHunter\Laraguard\Contracts\TwoFactorAuthenticatable {}
}

namespace App\Models{
/**
 * App\Models\Analytics
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property \Illuminate\Support\Carbon $date
 * @property int|null $displays
 * @property int|null $interactions
 * @property int|null $ignores
 * @property int|null $accept_all
 * @property int|null $deny_all
 * @property int|null $custom_choice
 * @property string|null $country
 * @property string|null $device_type
 * @property string|null $os
 * @property string|null $browser
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $accept_rate
 * @property-read float $interaction_rate
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics query()
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereAcceptAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereCustomChoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereDenyAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereDisplays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereIgnores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereInteractions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytics whereUpdatedAt($value)
 */
	class Analytics extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AppearanceSettings
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string|null $layout_type
 * @property int|null $show_deny_all
 * @property string|null $more_info_type
 * @property int|null $hide_language_switch
 * @property string|null $background_color
 * @property string|null $text_color
 * @property string|null $link_color
 * @property string|null $tab_color
 * @property string|null $accent_color
 * @property int|null $border_radius
 * @property int|null $background_shadow
 * @property int|null $background_overlay
 * @property string|null $overlay_color
 * @property int|null $overlay_opacity
 * @property string|null $logo_url
 * @property string|null $logo_position
 * @property string|null $logo_alt_tag
 * @property string|null $font_family
 * @property string|null $font_size
 * @property string|null $deny_button_bg
 * @property string|null $deny_button_text
 * @property string|null $save_button_bg
 * @property string|null $save_button_text
 * @property int|null $button_corner_radius
 * @property string|null $active_toggle_bg
 * @property string|null $active_toggle_icon
 * @property string|null $inactive_toggle_bg
 * @property string|null $inactive_toggle_icon
 * @property string|null $disabled_toggle_bg
 * @property string|null $disabled_toggle_icon
 * @property string|null $privacy_button_icon
 * @property string|null $privacy_button_bg
 * @property string|null $privacy_button_icon_color
 * @property string|null $privacy_button_desktop_size
 * @property string|null $privacy_button_mobile_size
 * @property int|null $custom_css_enabled
 * @property string|null $custom_css
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereAccentColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereActiveToggleBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereActiveToggleIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereBackgroundOverlay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereBackgroundShadow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereBorderRadius($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereButtonCornerRadius($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereCustomCss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereCustomCssEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereDenyButtonBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereDenyButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereDisabledToggleBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereDisabledToggleIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereFontFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereFontSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereHideLanguageSwitch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereInactiveToggleBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereInactiveToggleIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereLayoutType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereLinkColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereLogoAltTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereLogoPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereMoreInfoType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereOverlayColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereOverlayOpacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings wherePrivacyButtonBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings wherePrivacyButtonDesktopSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings wherePrivacyButtonIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings wherePrivacyButtonIconColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings wherePrivacyButtonMobileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereSaveButtonBg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereSaveButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereShowDenyAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereTabColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereTextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppearanceSettings whereUpdatedAt($value)
 */
	class AppearanceSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CategoryTranslation
 *
 * @property int $id
 * @property int $category_id
 * @property string $language
 * @property string $field
 * @property string $translation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereTranslation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoryTranslation whereUpdatedAt($value)
 */
	class CategoryTranslation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClientProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string $signup_as
 * @property string|null $industry_name
 * @property string|null $number_of_people
 * @property string|null $number_of_domain
 * @property string|null $work_type
 * @property string|null $visitors_pm
 * @property string|null $looking_for
 * @property string|null $first_hear
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereFirstHear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereIndustryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereLookingFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereNumberOfDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereNumberOfPeople($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereSignupAs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereVisitorsPm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientProfile whereWorkType($value)
 */
	class ClientProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $vat_id
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $website
 * @property string|null $industry
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $subscription_plan
 * @property string|null $subscription_status
 * @property string|null $subscription_expires_at
 * @property int|null $max_domains
 * @property int|null $custom_branding
 * @property int|null $api_access
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $street
 * @property string|null $zip_code
 * @property string|null $billing_account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereApiAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereBillingAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCustomBranding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereIndustry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereLegalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereMaxDomains($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSubscriptionExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSubscriptionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSubscriptionStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereVatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereZipCode($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CompanyUser
 *
 * @property int $id
 * @property int $company_id
 * @property int $user_id
 * @property string|null $role
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyUser whereUserId($value)
 */
	class CompanyUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Configuration
 *
 * @property int $id
 * @property int $company_id
 * @property string $name
 * @property string $framework_type
 * @property string $framework_region
 * @property string|null $domain
 * @property string|null $status
 * @property string|null $data_controller
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $hash_key
 * @property-read \App\Models\Company $company
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration query()
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereDataController($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereFrameworkRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereFrameworkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereHashKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Configuration whereUpdatedAt($value)
 */
	class Configuration extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ConsentAnalytics
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string $date
 * @property int|null $displays
 * @property int|null $interactions
 * @property int|null $ignores
 * @property int|null $accept_all
 * @property int|null $deny_all
 * @property int|null $custom_choice
 * @property string|null $country
 * @property string|null $device_type
 * @property string|null $os
 * @property string|null $browser
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereAcceptAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereCustomChoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereDenyAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereDisplays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereIgnores($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereInteractions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentAnalytics whereUpdatedAt($value)
 */
	class ConsentAnalytics extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ConsentHistory
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array|null $consent_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereConsentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsentHistory whereUserId($value)
 */
	class ConsentHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContactSale
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $country
 * @property string|null $company_name
 * @property string $searching_for
 * @property string|null $message
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereSearchingFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactSale whereUpdatedAt($value)
 */
	class ContactSale extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ContentSettings
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string|null $first_layer_title
 * @property string|null $first_layer_message
 * @property bool|null $mobile_specific_message
 * @property string|null $mobile_message
 * @property string|null $legal_notice_text
 * @property string|null $legal_notice_url
 * @property string|null $privacy_policy_text
 * @property string|null $privacy_policy_url
 * @property string|null $second_layer_title
 * @property string|null $second_layer_description
 * @property string|null $services_title
 * @property string|null $services_description
 * @property string|null $categories_title
 * @property string|null $categories_description
 * @property string|null $accept_all_button
 * @property string|null $deny_all_button
 * @property string|null $save_button
 * @property string|null $accept_button_label
 * @property string|null $deny_button_label
 * @property string|null $more_info_label
 * @property string|null $service_provider_label
 * @property string|null $privacy_policy_label
 * @property string|null $legitimate_interest_label
 * @property string|null $storage_info_label
 * @property string|null $save_settings_label
 * @property string|null $accept_selected_label
 * @property string|null $essential_category_label
 * @property string|null $marketing_category_label
 * @property string|null $functional_category_label
 * @property string|null $analytics_category_label
 * @property string|null $active_status_label
 * @property string|null $inactive_status_label
 * @property string|null $required_status_label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $about_title
 * @property string|null $about_description
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereAboutDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereAboutTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereAcceptAllButton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereAcceptButtonLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereAcceptSelectedLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereActiveStatusLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereAnalyticsCategoryLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereCategoriesDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereCategoriesTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereDenyAllButton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereDenyButtonLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereEssentialCategoryLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereFirstLayerMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereFirstLayerTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereFunctionalCategoryLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereInactiveStatusLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereLegalNoticeText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereLegalNoticeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereLegitimateInterestLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereMarketingCategoryLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereMobileMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereMobileSpecificMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereMoreInfoLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings wherePrivacyPolicyLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings wherePrivacyPolicyText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings wherePrivacyPolicyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereRequiredStatusLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereSaveButton($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereSaveSettingsLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereSecondLayerDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereSecondLayerTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereServiceProviderLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereServicesDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereServicesTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereStorageInfoLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentSettings whereUpdatedAt($value)
 */
	class ContentSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DataProcessingServices
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string $name
 * @property string|null $cookie_name
 * @property string|null $domain
 * @property string|null $template_id
 * @property \App\Models\ServiceCategory|null $category
 * @property string|null $status
 * @property string|null $description
 * @property string|null $provider_name
 * @property string|null $provider_url
 * @property string|null $privacy_policy_url
 * @property string|null $processing_purpose
 * @property string|null $data_categories
 * @property string|null $data_retention
 * @property string|null $version
 * @property bool|null $is_essential
 * @property bool|null $data_sharing_eu
 * @property bool|null $accepted_by_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices query()
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereAcceptedByDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereCookieName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereDataCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereDataRetention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereDataSharingEu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereIsEssential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices wherePrivacyPolicyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereProcessingPurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereProviderUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataProcessingServices whereVersion($value)
 */
	class DataProcessingServices extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DpsLibrary
 *
 * @property int $id
 * @property string|null $cookie_id
 * @property string $name
 * @property string $domain_pattern
 * @property string $category
 * @property string|null $description
 * @property string|null $provider_name
 * @property string|null $provider_url
 * @property string|null $privacy_policy_url
 * @property string|null $logo_url
 * @property string|null $script_patterns
 * @property string|null $cookie_patterns
 * @property string|null $data_collected
 * @property string|null $data_retention
 * @property bool|null $data_sharing
 * @property bool|null $is_official
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereCookieId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereCookiePatterns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereDataCollected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereDataRetention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereDataSharing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereDomainPattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereIsOfficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary wherePrivacyPolicyUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereProviderUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereScriptPatterns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsLibrary whereUpdatedAt($value)
 */
	class DpsLibrary extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DpsScan
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string $domain
 * @property string|null $service_name
 * @property string|null $service_url
 * @property string|null $source_domain
 * @property string|null $category
 * @property string|null $detection_type
 * @property string|null $cookie_name
 * @property string|null $display_name
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $scan_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereCookieName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereDetectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereScanDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereServiceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereServiceUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereSourceDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpsScan whereUpdatedAt($value)
 */
	class DpsScan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Framework
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $region
 * @property string|null $logo
 * @property string|null $description
 * @property string|null $legal_url
 * @property int|null $is_require_prior_concent
 * @property int|null $is_require_withdrawal_option
 * @property int|null $is_legal_require_basis
 * @property int|null $is_cookies_categoray_require
 * @property int|null $consent_expiry_days
 * @property int|null $show_accept_all
 * @property int|null $show_reject_all
 * @property int|null $show_settings_link
 * @property string|null $default_consent_state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Framework newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Framework newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Framework query()
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereConsentExpiryDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereDefaultConsentState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereIsCookiesCategorayRequire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereIsLegalRequireBasis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereIsRequirePriorConcent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereIsRequireWithdrawalOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereLegalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereShowAcceptAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereShowRejectAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereShowSettingsLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Framework whereUpdatedAt($value)
 */
	class Framework extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ImplementationSettings
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string|null $blocking_mode
 * @property string|null $current_version
 * @property string|null $scan_frequency
 * @property bool|null $auto_populate
 * @property bool|null $include_trackers
 * @property \Illuminate\Support\Carbon|null $last_scan_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereAutoPopulate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereBlockingMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereCurrentVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereIncludeTrackers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereLastScanDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereScanFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImplementationSettings whereUpdatedAt($value)
 */
	class ImplementationSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property int $company_id
 * @property int $inviter_id
 * @property string $email
 * @property string $token
 * @property string|null $role
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\User $inviter
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereInviterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
 */
	class Invitation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NewCategory
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string $name
 * @property string|null $description
 * @property string|null $identifier
 * @property bool|null $is_essential
 * @property bool|null $is_default
 * @property int|null $order_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereIsEssential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereOrderIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewCategory whereUpdatedAt($value)
 */
	class NewCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NewsletterSubscription
 *
 * @property int $id
 * @property string $email
 * @property bool|null $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsletterSubscription whereUpdatedAt($value)
 */
	class NewsletterSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PricePlan
 *
 * @property int $id
 * @property string|null $membership
 * @property string|null $description
 * @property int|null $domain_allowed
 * @property int|null $level
 * @property float|null $price_month
 * @property float|null $price_yearly
 * @property int|null $max_domain
 * @property bool|null $is_custom
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $max_visitors
 * @property int|null $max_languages
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereDomainAllowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereIsCustom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereMaxDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereMaxLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereMaxVisitors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereMembership($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan wherePriceMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan wherePriceYearly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricePlan whereUpdatedAt($value)
 */
	class PricePlan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ServiceCategories
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string $name
 * @property string|null $description
 * @property string|null $identifier
 * @property int|null $is_essential
 * @property int|null $is_default
 * @property int|null $order_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DataProcessingServices[] $services
 * @property-read int|null $services_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereIsEssential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereOrderIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategories whereUpdatedAt($value)
 */
	class ServiceCategories extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ServiceCategory
 *
 * @property int $id
 * @property int $company_id
 * @property int $configuration_id
 * @property string $name
 * @property string|null $description
 * @property string|null $identifier
 * @property bool|null $is_essential
 * @property bool|null $is_default
 * @property int|null $order_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Configuration $configuration
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DataProcessingServices[] $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CategoryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereConfigurationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereIsEssential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereOrderIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereUpdatedAt($value)
 */
	class ServiceCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $expire_at
 * @property float|null $price_paid
 * @property bool|null $is_life_time
 * @property string|null $coupon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $price_plan_id
 * @property int $user_id
 * @property-read \App\Models\PricePlan $pricePlan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereIsLifeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePricePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePricePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SubscriptionHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $price_plan_id
 * @property string $amount
 * @property string $currency
 * @property string|null $payment_method
 * @property string|null $payment_reference
 * @property string $status
 * @property array|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PricePlan $pricePlan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory wherePricePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionHistory whereUserId($value)
 */
	class SubscriptionHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $password_changed_at
 * @property int $active
 * @property string|null $timezone
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $last_login_ip
 * @property int $to_be_logged_out
 * @property string|null $provider
 * @property string|null $provider_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $company_id
 * @property string|null $role
 * @property int|null $price_plan_id
 * @property int|null $agency_id
 * @property string|null $reset_otp
 * @property string|null $otp_expires_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Company[] $companies
 * @property-read int|null $companies_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAgencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePricePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereResetOtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereToBeLoggedOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

