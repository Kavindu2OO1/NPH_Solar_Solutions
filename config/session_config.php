<?php
return [
    'session_roles' => [
        'Admin' => [
            'duration' => 3600,  // 1 hour 3600
            'allowed_pages' => [
                'admin_dashboard.php',
                'admin_reports.php'
            ]
        ],
        'Manager' => [
            'duration' => 7200,  // 2 hours
            'allowed_pages' => [
                'manager_dashboard.php',
                'manager_tasks.php'
            ]
        ],
        'Delivery_Personnel' => [
            'duration' => 5400,  // 1.5 hours
            'allowed_pages' => [
                'delivery_tracking.php',
                'delivery_tasks.php'
            ]
        ],
        'User' => [
            'duration' => 3600,  // 1 hour
            'allowed_pages' => [
                'user_profile.php',
                'user_dashboard.php'
            ]
        ]
    ],
    'global_settings' => [
        'session_name' => 'MYSECUREAPP_SESSION',
        'cookie_path' => '/',
        'cookie_domain' => '', 
        'secure' => true,
        'httponly' => true
    ]
];