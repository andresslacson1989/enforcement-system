<?php

/**
 * Official Roles
 * 'super-admin' => 'root',
 * 'president' => 'president',
 * 'vice president' => 'vice president',
 * 'general manager' => 'general manager',
 * 'accounting manager' => 'accounting manager',
 * 'senior accounting manager' => 'senior accounting manager',
 * 'account specialist' => 'account specialist',
 * 'hr manager' => 'hr manager',
 * 'hr specialist' => 'hr specialist',
 * 'operation manager' => 'operation manager',
 * 'assigned officer' => 'assigned officer',
 * 'detachment commander' => 'detachment commander',
 * 'officer in charge' => 'officer in charge',
 * 'security in charge' => 'security in charge',
 * 'cluster head guard' => 'cluster head guard',
 * 'head guard' => 'head guard',
 * 'assistant head guard' => 'assistant head guard',
 * 'security guard' => 'security guard',
 * 'lady guard' => 'lady guard',
 */

return [
    'super-admin' => [
        'super admin' => [
            'name' => 'root',
            'description' => 'All Access',
        ],
    ],
    'admin' => [
        'president' => [
            'name' => 'president',
            'description' => 'Admin Access',
        ],
        'vice president' => [
            'name' => 'vice president',
            'description' => 'Admin Access',
        ],
        'general manager' => [
            'name' => 'general manager',
            'description' => 'Admin Access', ],
    ],
    'staff' => [
        'accounting manager' => [
            'name' => 'accounting manager',
            'description' => 'Accounting Manager',
        ],
        'senior accounting manager' => [
            'name' => 'senior accounting manager',
            'description' => 'Senior Accounting Manager',
        ],
        'accounting specialist' => [
            'name' => 'accounting specialist',
            'description' => 'Accounting Specialist',
        ],
        'hr manager' => [
            'name' => 'hr manager',
            'description' => 'HR Manager',
        ],
        'hr specialist' => [
            'name' => 'hr specialist',
            'description' => 'HR Specialist',
        ],
        'operation manager' => [
            'name' => 'operation manager',
            'description' => 'Operation Specialist',
        ],
    ],
    'personnel-admin' => [
        'assigned officer' => [
            'name' => 'assigned officer',
            'description' => 'Assigned Officer',
        ],
        'detachment commander' => [
            'name' => 'detachment commander',
            'description' => 'Detachment Commander',
        ],
        'officer in charge' => [
            'name' => 'officer in charge',
            'description' => 'Officer In Charge',
        ],
        'security in charge' => [
            'name' => 'security in charge',
            'description' => 'Security In Charge',
        ],

    ],
    'personnel-base' => [
        'cluster head guard' => [
            'name' => 'cluster head guard',
            'description' => 'Cluster Head Guard',
        ],
        'head guard' => [
            'name' => 'head guard',
            'description' => 'Head Guard',
        ],
        'security guard' => [
            'name' => 'security guard',
            'description' => 'Security Guard',
        ],
        'lady guard' => [
            'name' => 'lady guard',
            'description' => 'Lady Guard',
        ],
    ],
];
