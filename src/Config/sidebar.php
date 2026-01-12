<?php

return [
    [
        'title'      => 'me::me.Dashboard-ME',
        'icon'       => 'fas fa-tachometer-alt',
        'route'      => 'me.dashboard',
        'for_active' => 'me.dashboard',
        'icon_color' => 'text-encodex-secondary',
        'permit'     => 'me.dashboard',
        'sl'         => 1,
    ],

    [
        'title'      => 'DEVELOPER-ME',
        'icon'       => 'fas fa-users-cog',
        'icon_color' => 'text-success',
        'sl'         => 2,
        'children' => [
            [
                'icon'       => 'fas fa-users',
                'title'      => 'Users',
                'route'      => 'me.users.index',
                'for_active' => 'me.users',
                'permit'     => 'me_user.view',
                'icon_color' => 'text-success',
            ],
            [
                'icon'   => 'fas fa-user-shield',
                'title'  => 'Roles',
                'route'  => 'me.roles.index',
                'for_active' => 'me.roles',
                'permit' => 'me_role.view',
                'icon_color' => 'text-success',
            ],
            [
                'permit' => 'me_setting.configurations',
                'title'  => 'Configurations',
                'icon'   => 'fas fa-wrench',
                'route'  => 'me.configurations.edit',
                'for_active' => 'me.configurations.edit',
                'icon_color' => 'text-danger',
            ],
            [
                'icon'   => 'fas fa-trash-alt',
                'title'  => 'Clear Data',
                'route'  => 'me.data.clear.form',
                'for_active'  => 'me.data.clear.form',
                'permit' => 'me_clearData',
                'icon_color' => 'text-danger',
            ],
        ]
    ],

];



