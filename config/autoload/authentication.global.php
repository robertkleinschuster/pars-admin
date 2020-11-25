<?php
return [
    'authentication' => [
        'username' => 'login_username',
        'password' => 'login_password',
        'redirect' => [
            'controller' => 'auth',
            'action' => 'login'
        ],
        'whitelist' => [
            [
                'controller' => 'auth',
                'action' => 'login'
            ],
            [
                'controller' => 'setup',
                'action' => 'index'
            ]
        ],
    ],
];
