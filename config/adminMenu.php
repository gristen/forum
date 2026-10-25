<?php
return [
    'menu' => [
        [
            'route' => 'admin.index',
            'active' => 'admin.index',
            'icon' => 'bi-speedometer2',
            'title' => 'Дашборд',
        ],
        [
            'route' => 'admin.users.index',
            'active' => 'admin.users.*',
            'icon' => 'bi-people-fill',
            'title' => 'Пользователи',
        ],
        [
            'route' => 'admin.roles.index',
            'active' => 'admin.roles.*',
            'icon' => 'bi-person-badge-fill',
            'title' => 'Роли',
        ],
        [
            'route' => 'admin.categories.index',
            'active' => 'admin.categories.*',
            'icon' => 'bi-folder-fill',
            'title' => 'Категории',
        ],
        /*[
            'route' => 'admin.topics.index',
            'active' => 'admin.topics.*',
            'icon' => 'bi-chat-square-text-fill',
            'title' => 'Темы',
        ],
        [
            'route' => 'admin.messages.index',
            'active' => 'admin.messages.*',
            'icon' => 'bi-chat-fill',
            'title' => 'Сообщения',
        ],*/
        /*[
            'route' => 'admin.users.index',
            'active' => 'admin.users.*',
            'icon' => 'bi-people-fill',
             [
                 'route' => 'admin.permissions.index',
                 'active' => 'admin.permissions.*',
                 'icon' => 'bi-shield-lock-fill',
                 'title' => 'Права',
             ],*/
            /* [
                 'route' => 'admin.reports.index',
                 'active' => 'admin.reports.*',
                 'icon' => 'bi-flag-fill',
                 'title' => 'Жалобы',
             ],
            'title' => 'Пользователи',
        ],*/
    ],


];

