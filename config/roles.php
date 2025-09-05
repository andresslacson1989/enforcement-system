<?php

/**
 * ----------------------------------------------------------------------------------
 * Official Application Roles
 * ----------------------------------------------------------------------------------
 * This file serves as the initial source of roles for all user roles within the system.
 * Roles are grouped by category for organizational purposes.
 * Roles are also grouped by department for the separation of Staffs Departments and Personnel Detachments.
 *
 * The 'name' of each role should be in snake case, as this is the identifier
 * stored in the database and used by the Spatie/Permission package.
 *
 * Categories:
 * - super-admin: The root user with all permissions.
 * - admin: Top-level management with broad administrative access.
 * - staff: Office-based administrative personnel (HR, Accounting, etc.).
 * - personnel-admin: Field leadership roles responsible for managing detachments.
 * - personnel-base: The main security workforce.
 * ----------------------------------------------------------------------------------
 */

return [

    'super admin' => [
        'name' => 'root',
        'description' => 'All Access',
        'department' => 'Developer',
        'group' => 'Super Admin',
    ],

    'president' => [
        'name' => 'president',
        'description' => 'Top-level executive with full administrative access.',
        'department' => 'admin',
        'group' => 'admin',
    ],
    'vice president' => [
        'name' => 'vice president',
        'description' => 'Second-in-command with full administrative access.',
        'department' => 'admin',
        'group' => 'admin',
    ],
    'general manager' => [
        'name' => 'general manager',
        'description' => 'Oversees all company operations with administrative access.',
        'department' => 'admin',
        'group' => 'admin',
    ],

    'senior accounting manager' => [
        'name' => 'senior accounting manager',
        'description' => 'Senior Accounting Manager',
        'department' => 'accounting',
        'group' => 'staff',
    ],
    'accounting manager' => [
        'name' => 'accounting manager',
        'description' => 'Accounting Manager',
        'department' => 'accounting',
        'group' => 'staff',
    ],
    'accounting specialist' => [
        'name' => 'accounting specialist',
        'description' => 'Accounting Specialist',
        'department' => 'accounting',
        'group' => 'staff',
    ],
    'hr manager' => [
        'name' => 'hr manager',
        'description' => 'HR Manager',
        'department' => 'hr',
        'group' => 'staff',
    ],
    'hr specialist' => [
        'name' => 'hr specialist',
        'description' => 'HR Specialist',
        'department' => 'hr',
        'group' => 'staff',
    ],
    'operation manager' => [
        'name' => 'operation manager',
        'description' => 'Handles overall management of Single Posts (1-2 guards) directly.',
        'department' => 'operations',
        'group' => 'staff',
    ],

    'assigned officer' => [
        'name' => 'assigned officer',
        'description' => 'A generic officer role assigned to a detachment, with specific duties determined by detachment size.',
        'department' => 'personnel_admin',
        'group' => 'personnel_admin',
    ],
    'detachment commander' => [
        'name' => 'detachment commander',
        'description' => 'Overall in charge of a Large Detachment (61+ guards).',
        'department' => 'personnel_admin',
        'group' => 'personnel_admin',
    ],
    'assistant detachment commander' => [
        'name' => 'assistant detachment commander',
        'description' => 'Second in command in a Large Detachment, assists the Detachment Commander.',
        'department' => 'personnel_admin',
        'group' => 'personnel_admin',
    ],
    'officer in charge' => [
        'name' => 'officer in charge',
        'description' => 'Overall in charge of a Medium Detachment (11_60 guards).',
        'department' => 'personnel_admin',
        'group' => 'personnel_admin',
    ],
    'security in charge' => [
        'name' => 'security in charge',
        'description' => 'Second in command in a Medium Detachment, assists the Officer In Charge.',
        'department' => 'personnel_admin',
        'group' => 'personnel_admin',
    ],

    'cluster head guard' => [
        'name' => 'cluster head guard',
        'description' => 'Supervises security guards within a specific cluster or unit in a Large or Medium Detachment.',
        'department' => 'personnel_base',
        'group' => 'personnel_base',
    ],
    'head guard' => [
        'name' => 'head guard',
        'description' => 'In charge of a Small Team (3_10 guards).',
        'department' => 'personnel_base',
        'group' => 'personnel_base',
    ],
    'guard' => [
        'name' => 'guard',
        'description' => 'The main security force personnel for all detachment sizes.',
        'department' => 'personnel_base',
        'group' => 'personnel_base',
    ],

];
