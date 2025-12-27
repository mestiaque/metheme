<?php

return [
    [
        'title' => 'Dashboard-ME',
        'icon'  => 'fas fa-tachometer-alt',
        'route' => 'me.dashboard',
        'icon_color' => 'text-encodex-secondary',
        'permit' => 'me.dashboard'
    ],

    [
        'title'    => 'DEVELOPER-ME',
        'icon'     => 'fas fa-users-cog',
        'icon_color' => 'text-success',
        'children' => [
            [
                'icon'   => 'fas fa-users',
                'title'  => 'Users',
                'route'  => 'me.users.index',
                'permit' => 'me_user.view',
                'icon_color' => 'text-success',
            ],
            [
                'icon'   => 'fas fa-user-shield',
                'title'  => 'Roles',
                'route'  => 'me.roles.index',
                'permit' => 'me_role.view',
                'icon_color' => 'text-success',
            ],
            [
                'permit' => 'me_setting.configurations',
                'title'  => 'Configurations',
                'icon'   => 'fas fa-wrench',
                'route'  => 'me.configurations.edit',
                'icon_color' => 'text-danger',
            ],
            [
                'icon'   => 'fas fa-trash-alt',
                'title'  => 'Clear Data',
                'route'  => 'me.data.clear.form',
                'permit' => 'me_clearData',
                'icon_color' => 'text-danger',
            ],
        ]
    ],

];



