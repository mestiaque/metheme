<?php

return [
    [
        'title' => 'Dashboard-ME',
        'icon'  => 'fas fa-tachometer-alt',
        'route' => 'encodex.dashboard',
        'icon_color' => 'text-encodex-secondary',
        'permit' => 'encodex.dashboard'
    ],

    [
        'title'    => 'User Management-ME',
        'icon'     => 'fas fa-users-cog',
        'icon_color' => 'text-success',
        'children' => [
            [
                'icon'   => 'fas fa-users',
                'title'  => 'Users',
                'route'  => 'encodex.users.index',
                'permit' => 'encodex_user.view',
                'icon_color' => 'text-success',
            ],
            [
                'icon'   => 'fas fa-user-shield',
                'title'  => 'Roles',
                'route'  => 'encodex.roles.index',
                'permit' => 'encodex_role.view',
                'icon_color' => 'text-success',
            ],
        ]
    ],

    [
        'title'    => 'Admin-ME',
        'icon'     => 'fas fa-cog',
        'icon_color' => 'text-danger',
        'children' => [
            [
                'permit' => 'encodex_setting.configurations',
                'title'  => 'Configurations',
                'icon'   => 'fas fa-wrench',
                'route'  => 'encodex.configurations.edit',
                'icon_color' => 'text-danger',
            ],
            [
                'icon'   => 'fas fa-trash-alt',
                'title'  => 'Clear Data',
                'route'  => 'encodex.data.clear.form',
                'permit' => 'encodex_clearData',
                'icon_color' => 'text-danger',
            ],
        ]

    ],

];



