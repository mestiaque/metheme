<?php

return [
    [
        'title'      => 'DEVELOPER-ME',
        'icon'       => 'fas fa-users-cog',
        'icon_color' => 'icc-28',
        'sl'         => 1000,
        'children' => [
            [
                'title'      => 'me::me.Dashboard-ME',
                'icon'       => 'fas fa-tachometer-alt',
                'route'      => 'me.dashboard',
                'for_active' => 'me.dashboard',
                'icon_color' => 'text-encodex-secondary',
                'permit'     => 'me.dashboard',
            ],
            [
                'icon'       => 'fas fa-users',
                'title'      => 'Users',
                'route'      => 'me.users.index',
                'for_active' => 'me.users',
                'permit'     => 'me_user.view',
                'icon_color' => 'icc-81',
            ],
            [
                'icon'   => 'fas fa-user-shield',
                'title'  => 'Roles',
                'route'  => 'me.roles.index',
                'for_active' => 'me.roles',
                'permit' => 'me_role.view',
                'icon_color' => 'icc-38',
            ],
            [
                'permit' => 'me_setting.configurations',
                'title'  => 'Configurations',
                'icon'   => 'fas fa-wrench',
                'route'  => 'me.configurations.edit',
                'for_active' => 'me.configurations.edit',
                'icon_color' => 'icc-67',
            ],
            [
                'icon'   => 'fas fa-trash-alt',
                'title'  => 'Clear Data',
                'route'  => 'me.data.clear.form',
                'for_active'  => 'me.data.clear.form',
                'permit' => 'me_clearData',
                'icon_color' => 'text-danger',
            ],
            [
                'icon'   => 'fas fa-paint-brush',
                'title'  => 'Theme Info',
                'route'  => 'me.theme',
                'for_active'  => 'me.theme',
                'permit' => 'me.theme',
                'icon_color' => 'icc-51',
            ],
        ]
    ],

];



