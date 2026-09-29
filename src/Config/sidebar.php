<?php

/*
|--------------------------------------------------------------------------
| Metheme sidebar
|--------------------------------------------------------------------------
| Route names have no prefix; URLs use config('me_settings.route_prefix').
| Items are sorted by "sl"; a group is hidden when none of its children
| are visible to the logged-in user.
*/

return [
    [
        'title'      => 'Dashboard',
        'icon'       => 'fas fa-tachometer-alt',
        'icon_color' => 'text-encodex-secondary',
        'route'      => 'dashboard',
        'for_active' => 'dashboard',
        'permit'     => 'me.dashboard',
        'sl'         => 1,
    ],
    [
        'title'      => 'Users & Roles',
        'icon'       => 'fas fa-users-cog',
        'icon_color' => 'icc-81',
        'sl'         => 1001,
        'children'   => [
            [
                'title'      => 'Users',
                'icon'       => 'fas fa-users',
                'route'      => 'users.index',
                'for_active' => 'users.',
                'permit'     => 'me_user.view',
                'icon_color' => 'icc-81',
            ],
            [
                'title'      => 'Roles',
                'icon'       => 'fas fa-user-shield',
                'route'      => 'roles.index',
                'for_active' => 'roles.',
                'permit'     => 'me_role.view',
                'icon_color' => 'icc-38',
            ],
        ],
    ],
    [
        'title'      => 'Configuration',
        'icon'       => 'fas fa-sliders-h',
        'icon_color' => 'icc-67',
        'sl'         => 1002,
        'children'   => [
            [
                'title'      => 'Configurations',
                'icon'       => 'fas fa-wrench',
                'route'      => 'configurations.edit',
                'for_active' => 'configurations.',
                'permit'     => 'me_setting.configurations',
                'icon_color' => 'icc-67',
            ],
            [
                'title'      => 'Mail Configuration',
                'icon'       => 'fas fa-at',
                'route'      => 'mail-config.edit',
                'for_active' => 'mail-config.',
                'permit'     => 'me_setting.mail',
                'icon_color' => 'icc-38',
            ],
            [
                'title'      => 'SMS Configuration',
                'icon'       => 'fas fa-sms',
                'route'      => 'sms-config.edit',
                'for_active' => 'sms-config.',
                'permit'     => 'me_setting.sms',
                'icon_color' => 'icc-81',
            ],
            [
                'title'      => 'Menus',
                'icon'       => 'fas fa-bars',
                'route'      => 'menus.index',
                'for_active' => 'menus.',
                'permit'     => 'me_menus.view',
                'icon_color' => 'text-primary',
            ],
            [
                'title'      => 'Clear Data',
                'icon'       => 'fas fa-trash-alt',
                'route'      => 'data.clear.form',
                'for_active' => 'data.clear',
                'permit'     => 'me.clearData',
                'icon_color' => 'text-danger',
            ],
        ],
    ],
    [
        'title'      => 'Logs',
        'icon'       => 'fas fa-clipboard-list',
        'icon_color' => 'icc-40',
        'sl'         => 1003,
        'children'   => [
            [
                'title'      => 'Activity Log',
                'icon'       => 'fas fa-history',
                'route'      => 'activity.index',
                'for_active' => 'activity.',
                'permit'     => 'me_activity.view',
                'icon_color' => 'icc-40',
            ],
            [
                'title'      => 'SMS Log & Balance',
                'icon'       => 'fas fa-comment-dollar',
                'route'      => 'sms-log.index',
                'for_active' => 'sms-log.',
                'permit'     => 'me_sms.view',
                'icon_color' => 'icc-40',
            ],
            [
                'title'      => 'Mail Log',
                'icon'       => 'fas fa-envelope-open-text',
                'route'      => 'mail-log.index',
                'for_active' => 'mail-log.',
                'permit'     => 'me_mail.view',
                'icon_color' => 'icc-55',
            ],
        ],
    ],
    [
        'title'      => 'Theme',
        'icon'       => 'fas fa-paint-brush',
        'icon_color' => 'icc-55',
        'sl'         => 1004,
        'children'   => [
            [
                'title'      => 'Theme Info',
                'icon'       => 'fas fa-palette',
                'route'      => 'theme',
                'for_active' => 'theme',
                'permit'     => 'me.theme',
                'icon_color' => 'icc-55',
            ],
            [
                'title'      => 'Email Templates',
                'icon'       => 'fas fa-envelope',
                'route'      => 'mail-layout-preview',
                'for_active' => 'mail-layout-preview',
                'permit'     => 'me.mailLayoutPreview',
                'icon_color' => 'text-secondary',
            ],
        ],
    ],
];
