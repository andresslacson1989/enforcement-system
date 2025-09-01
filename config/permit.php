<?php

/**
 * System Permission using Spatie Roles and Permission
 * Centralize Editable Permission
 *
 * Name => Permission
 * Change the permission but not the name. then run php artisan db:seed to update permission
 */
return [
    // System Menu
    'System Menu' => 'group name',
    'view form library menu' => [
        'name' => 'view form library menu',
        'description' => 'Allows user to see the Form Library link in the sidebar.',
    ],
    'view detachment profile menu' => [
        'name' => 'view detachment profile menu',
        'description' => 'Allows user to see the Detachment Profile link in the sidebar.',
    ],
    'view my profile menu' => [
        'name' => 'view my profile menu',
        'description' => 'Allows user to see their own Profile link in the sidebar.',
    ],
    'view access menu' => [
        'name' => 'view access menu',
        'description' => 'Allows user to see the Access Control parent menu link in the sidebar.',
    ],
    'view roles menu' => [
        'name' => 'view roles menu',
        'description' => 'Allows user to see the Roles link under Access Control in the sidebar.',
    ],
    'view permissions menu' => [
        'name' => 'view permissions menu',
        'description' => 'Allows user to see the Permissions link under Access Control in the sidebar.',
    ],
    'view activity logs menu' => [
        'name' => 'view activity logs menu',
        'description' => 'Allows user to see the Activity Logs link in the sidebar.',
    ],
    'view staffs menu' => [
        'name' => 'view staffs menu',
        'description' => 'Allows user to see the Staffs directory link in the sidebar.',
    ],
    'view personnel menu' => [
        'name' => 'view personnel menu',
        'description' => 'Allows user to see the Personnel directory link in the sidebar.',
    ],
    'view detachments menu' => [
        'name' => 'view detachments menu',
        'description' => 'Allows user to see the Detachments directory link in the sidebar.',
    ],

    // Admin
    'Admin' => 'group name',
    'add admin' => [
        'name' => 'add admin',
        'description' => 'Allows user to add new admin users.',
    ],
    'edit admin' => [
        'name' => 'edit admin',
        'description' => 'Allows user to edit existing admin users.',
    ],
    'delete admin' => [
        'name' => 'delete admin',
        'description' => 'Allows user to delete admin users.',
    ],
    'view admin' => [
        'name' => 'view admin',
        'description' => 'Allows user to view admin users.',
    ],
    'change admin role' => [
        'name' => 'change admin role',
        'description' => 'Allows user to change the roles of admin users.',
    ],
    'suspend admin' => [
        'name' => 'suspend admin',
        'description' => 'Allows user to suspend admin users.',
    ],

    // Staff
    'Staff' => 'group name',
    'add staff' => [
        'name' => 'add staff',
        'description' => 'Allows user to add new staff members.',
    ],
    'edit staff' => [
        'name' => 'edit staff',
        'description' => 'Allows user to edit existing staff members.',
    ],
    'delete staff' => [
        'name' => 'delete staff',
        'description' => 'Allows user to delete staff members.',
    ],
    'view staffs' => [
        'name' => 'view staffs',
        'description' => 'Allows user to view the list of staff members.',
    ],
    'change staff role' => [
        'name' => 'change staff role',
        'description' => 'Allows user to change the roles of staff members.',
    ],
    'suspend staff' => [
        'name' => 'suspend staff',
        'description' => 'Allows user to suspend staff members.',
    ],

    // Personnel
    'Personnel' => 'group name',
    'add personnel' => [
        'name' => 'add personnel',
        'description' => 'Allows user to add new security personnel.',
    ],
    'edit personnel' => [
        'name' => 'edit personnel',
        'description' => 'Allows user to edit existing security personnel.',
    ],
    'delete personnel' => [
        'name' => 'delete personnel',
        'description' => 'Allows user to delete security personnel.',
    ],
    'view personnel' => [
        'name' => 'view personnel',
        'description' => 'Allows user to view the list of all security personnel.',
    ],
    'remove personnel' => [
        'name' => 'remove personnel',
        'description' => 'Allows user to remove a personnel from a detachment (sets them to floating).',
    ],
    'change personnel role' => [
        'name' => 'change personnel role',
        'description' => 'Allows user to change the roles of security personnel.',
    ],
    'suspend personnel' => [
        'name' => 'suspend personnel',
        'description' => 'Allows user to suspend security personnel.',
    ],
    'edit profile' => [
        'name' => 'view own personnel profile',
        'description' => 'Allows user to edit their own profile info.',
    ],
    'view own personnel profile' => [
        'name' => 'view own personnel profile',
        'description' => 'Allows user to view their own detailed profile page.',
    ],
    'view any personnel profile' => [
        'name' => 'view any personnel profile',
        'description' => 'Allows user to view the detailed profile page of any personnel.',
    ],
    'view own detachment personnel' => [
        'name' => 'view own detachment personnel',
        'description' => 'Allows user to view the personnel roster of their own detachment.',
    ],
    'view any detachment personnel' => [
        'name' => 'view any detachment personnel',
        'description' => 'Allows user to view the personnel roster of any detachment.',
    ],
    'view own detachment personnel profile' => [
        'name' => 'view own detachment personnel profile',
        'description' => 'Allows user to view the profile of personnel within their own detachment.',
    ],

    // Roles
    'Roles' => 'group name',
    'add role' => [
        'name' => 'add role',
        'description' => 'Allows user to create new roles.',
    ],
    'edit role' => [
        'name' => 'edit role',
        'description' => 'Allows user to edit existing roles and their permissions.',
    ],
    'delete role' => [
        'name' => 'delete role',
        'description' => 'Allows user to delete roles.',
    ],
    'view role' => [
        'name' => 'view role',
        'description' => 'Allows user to view the list of roles.',
    ],

    // Detachments
    'Detachments' => 'group name',
    'add detachment' => [
        'name' => 'add detachment',
        'description' => 'Allows user to create new detachments.',
    ],
    'edit detachment' => [
        'name' => 'edit detachment',
        'description' => 'Allows user to edit existing detachment details.',
    ],
    'delete detachment' => [
        'name' => 'delete detachment',
        'description' => 'Allows user to delete detachments.',
    ],
    'view detachment' => [
        'name' => 'view detachment',
        'description' => 'Allows user to view the list of all detachments.',
    ],
    'view own detachment profile' => [
        'name' => 'view own detachment profile',
        'description' => 'Allows user to view the profile of their own assigned detachment.',
    ],
    'view any detachment profile' => [
        'name' => 'view any detachment profile',
        'description' => 'Allows user to view the profile of any detachment.',
    ],
    'add personnel to detachment' => [
        'name' => 'add personnel to detachment',
        'description' => 'Allows user to assign personnel to a detachment.',
    ],
    'approve detachment' => [
        'name' => 'approve detachment',
        'description' => 'Allows user to approve newly created detachments, making them active.',
    ],

    // Forms
    'Forms' => 'group name',
    'edit approved forms' => [
        'name' => 'edit approved forms',
        'description' => 'Allows user to edit forms that have already been approved.',
    ],
    'delete approved forms' => [
        'name' => 'delete approved forms',
        'description' => 'Allows user to delete forms that have already been approved.',
    ],
    'view approved forms' => [
        'name' => 'view approved forms',
        'description' => 'Allows user to view forms that have already been approved.',
    ],
    'print approved forms' => [
        'name' => 'print approved forms',
        'description' => 'Allows user to print forms that have already been approved.',
    ],
    'update processed form' => [
        'name' => 'update processed form',
        'description' => 'Allows user to update the processing status of a form (e.g., HR section).',
    ],
    'delete processed form' => [
        'name' => 'delete processed form',
        'description' => 'Allows user to delete forms that have been processed.',
    ],

    // Attendance Form
    'Attendance Forms' => 'group name',
    'fill attendance form' => [
        'name' => 'fill attendance form',
        'description' => 'Allows user to fill out and submit a new Attendance Form.',
    ],
    'edit attendance form' => [
        'name' => 'edit attendance form',
        'description' => 'Allows user to edit a submitted Attendance Form.',
    ],
    'view attendance form' => [
        'name' => 'view attendance form',
        'description' => 'Allows user to view submitted Attendance Forms.',
    ],
    'delete attendance form' => [
        'name' => 'delete attendance form',
        'description' => 'Allows user to delete an Attendance Form.',
    ],
    'confirm attendance form' => [
        'name' => 'confirm attendance form',
        'description' => 'Allows user to confirm or approve an Attendance Form.',
    ],

    // Payslip Form
    'Payslip Forms' => 'group name',
    'generate payslip form' => [
        'name' => 'generate payslip form',
        'description' => 'Allows user to generate new payslips.',
    ],
    'delete payslip form' => [
        'name' => 'delete payslip form',
        'description' => 'Allows user to delete generated payslips.',
    ],
    'edit payslip form' => [
        'name' => 'edit payslip form',
        'description' => 'Allows user to edit generated payslips.',
    ],
    'view payslip form' => [
        'name' => 'view payslip form',
        'description' => 'Allows user to view generated payslips.',
    ],
    'print payslip form' => [
        'name' => 'print payslip form',
        'description' => 'Allows user to print generated payslips.',
    ],

    // Leave Application Form
    'Leave Application Form' => 'group name',
    'fill leave application form' => [
        'name' => 'fill leave application form',
        'description' => 'Allows user to fill out and submit a new Leave Application Form.',
    ],
    'delete leave application form' => [
        'name' => 'delete leave application form',
        'description' => 'Allows user to delete a Leave Application Form.',
    ],
    'edit leave application form' => [
        'name' => 'edit leave application form',
        'description' => 'Allows user to edit a submitted Leave Application Form.',
    ],
    'view leave application form' => [
        'name' => 'view leave application form',
        'description' => 'Allows user to view submitted Leave Application Forms.',
    ],
    'approve leave application form' => [
        'name' => 'approve leave application form',
        'description' => 'Allows user to approve a submitted Leave Application Form.',
    ],
    'print leave application form' => [
        'name' => 'print leave application form',
        'description' => 'Allows user to print a Leave Application Form.',
    ],
    'process leave application form' => [
        'name' => 'process leave application form',
        'description' => 'Allows user to process an approved Leave Application Form.',
    ],

    // Requirement Transmittal Form
    'Requirement Transmittal Form' => 'group name',
    'fill requirement transmittal form' => [
        'name' => 'fill requirement transmittal form',
        'description' => 'Allows user to fill out and submit a new Requirement Transmittal Form for onboarding.',
    ],
    'delete requirement transmittal form' => [
        'name' => 'delete requirement transmittal form',
        'description' => 'Allows user to delete a Requirement Transmittal Form.',
    ],
    'edit requirement transmittal form' => [
        'name' => 'edit requirement transmittal form',
        'description' => 'Allows user to edit a submitted Requirement Transmittal Form.',
    ],
    'view requirement transmittal form' => [
        'name' => 'view requirement transmittal form',
        'description' => 'Allows user to view submitted Requirement Transmittal Forms.',
    ],
    'print requirement transmittal form' => [
        'name' => 'print requirement transmittal form',
        'description' => 'Allows user to print a Requirement Transmittal Form.',
    ],

    // First Month Performance Form
    'First Month Performance Form' => 'group name',
    'fill first month performance evaluation form' => [
        'name' => 'fill first month performance evaluation form',
        'description' => 'Allows user to fill out and submit a new 1st Month Performance Evaluation.',
    ],
    'delete first month performance evaluation form' => [
        'name' => 'delete first month performance evaluation form',
        'description' => 'Allows user to delete a 1st Month Performance Evaluation.',
    ],
    'edit first month performance evaluation form' => [
        'name' => 'edit first month performance evaluation form',
        'description' => 'Allows user to edit a submitted 1st Month Performance Evaluation.',
    ],
    'view first month performance evaluation form' => [
        'name' => 'view first month performance evaluation form',
        'description' => 'Allows user to view submitted 1st Month Performance Evaluations.',
    ],
    'print first month performance evaluation form' => [
        'name' => 'print first month performance evaluation form',
        'description' => 'Allows user to print a 1st Month Performance Evaluation.',
    ],
    'approve first month performance evaluation form' => [
        'name' => 'approve first month performance evaluation form',
        'description' => 'Allows user to approve a submitted 1st Month Performance Evaluation.',
    ],

    // Third Month Performance Form
    'Third Month Performance Form' => 'group name',
    'fill third month performance evaluation form' => [
        'name' => 'fill third month performance evaluation form',
        'description' => 'Allows user to fill out and submit a new 3rd Month Performance Evaluation.',
    ],
    'delete third month performance evaluation form' => [
        'name' => 'delete third month performance evaluation form',
        'description' => 'Allows user to delete a 3rd Month Performance Evaluation.',
    ],
    'edit third month performance evaluation form' => [
        'name' => 'edit third month performance evaluation form',
        'description' => 'Allows user to edit a submitted 3rd Month Performance Evaluation.',
    ],
    'view third month performance evaluation form' => [
        'name' => 'view third month performance evaluation form',
        'description' => 'Allows user to view submitted 3rd Month Performance Evaluations.',
    ],
    'print third month performance evaluation form' => [
        'name' => 'print third month performance evaluation form',
        'description' => 'Allows user to print a 3rd Month Performance Evaluation.',
    ],
    'approve third month performance evaluation form' => [
        'name' => 'approve third month performance evaluation form',
        'description' => 'Allows user to approve a submitted 3rd Month Performance Evaluation.',
    ],

    // Sixth Month Performance Form
    'Sixth Month Performance Form' => 'group name',
    'fill sixth month performance evaluation form' => [
        'name' => 'fill sixth month performance evaluation form',
        'description' => 'Allows user to fill out and submit a new 6th Month Performance Evaluation.',
    ],
    'delete sixth month performance evaluation form' => [
        'name' => 'delete sixth month performance evaluation form',
        'description' => 'Allows user to delete a 6th Month Performance Evaluation.',
    ],
    'edit sixth month performance evaluation form' => [
        'name' => 'edit sixth month performance evaluation form',
        'description' => 'Allows user to edit a submitted 6th Month Performance Evaluation.',
    ],
    'view sixth month performance evaluation form' => [
        'name' => 'view sixth month performance evaluation form',
        'description' => 'Allows user to view submitted 6th Month Performance Evaluations.',
    ],
    'print sixth month performance evaluation form' => [
        'name' => 'print sixth month performance evaluation form',
        'description' => 'Allows user to print a 6th Month Performance Evaluation.',
    ],
    'approve sixth month performance evaluation form' => [
        'name' => 'approve sixth month performance evaluation form',
        'description' => 'Allows user to approve a submitted 6th Month Performance Evaluation.',
    ],

    // ID Application Form
    'ID Application Form' => 'group name',
    'fill id application form' => [
        'name' => 'fill id application form',
        'description' => 'Allows user to fill out and submit a new ID Application Form.',
    ],
    'delete id application form' => [
        'name' => 'delete id application form',
        'description' => 'Allows user to delete an ID Application Form.',
    ],
    'edit id application form' => [
        'name' => 'edit id application form',
        'description' => 'Allows user to edit a submitted ID Application Form.',
    ],
    'view id application form' => [
        'name' => 'view id application form',
        'description' => 'Allows user to view submitted ID Application Forms.',
    ],
    'print id application form' => [
        'name' => 'print id application form',
        'description' => 'Allows user to print an ID Application Form.',
    ],

    // Personnel Requisition Form
    'Personnel Requisition Form' => 'group name',
    'fill personnel requisition form' => [
        'name' => 'fill personnel requisition form',
        'description' => 'Allows user to fill out and submit a new Personnel Requisition Form.',
    ],
    'delete personnel requisition form' => [
        'name' => 'delete personnel requisition form',
        'description' => 'Allows user to delete a Personnel Requisition Form.',
    ],
    'edit personnel requisition form' => [
        'name' => 'edit personnel requisition form',
        'description' => 'Allows user to edit a submitted Personnel Requisition Form.',
    ],
    'view personnel requisition form' => [
        'name' => 'view personnel requisition form',
        'description' => 'Allows user to view submitted Personnel Requisition Forms.',
    ],
    'print personnel requisition form' => [
        'name' => 'print personnel requisition form',
        'description' => 'Allows user to print a Personnel Requisition Form.',
    ],

    // Supply Requisition Form
    'Supply Requisition Form' => 'group name',
    'fill supply requisition form' => [
        'name' => 'fill supply requisition form',
        'description' => 'Allows user to fill out and submit a new Supply Requisition Form.',
    ],
    'delete supply requisition form' => [
        'name' => 'delete supply requisition form',
        'description' => 'Allows user to delete a Supply Requisition Form.',
    ],
    'edit supply requisition form' => [
        'name' => 'edit supply requisition form',
        'description' => 'Allows user to edit a submitted Supply Requisition Form.',
    ],
    'view supply requisition form' => [
        'name' => 'view supply requisition form',
        'description' => 'Allows user to view submitted Supply Requisition Forms.',
    ],
    'print supply requisition form' => [
        'name' => 'print supply requisition form',
        'description' => 'Allows user to print a Supply Requisition Form.',
    ],
    'approve supply requisition form' => [
        'name' => 'approve supply requisition form',
        'description' => 'Allows user to approve a submitted Supply Requisition Form.',
    ],
    'acknowledge receipt' => [
        'name' => 'acknowledge receipt',
        'description' => 'Allows user to acknowledge the receipt of supplies.',
    ],

    // Applicant Application Form
    'Applicant Application Form' => 'group name',
    'fill applicant application form' => [
        'name' => 'fill applicant application form',
        'description' => 'Allows user to fill out and submit a new Applicant Application Form.',
    ],
    'delete applicant application form' => [
        'name' => 'delete applicant application form',
        'description' => 'Allows user to delete an Applicant Application Form.',
    ],
    'view applicant application form' => [
        'name' => 'view applicant application form',
        'description' => 'Allows user to view submitted Applicant Application Forms.',
    ],
    'edit applicant application form' => [
        'name' => 'edit applicant application form',
        'description' => 'Allows user to edit a submitted Applicant Application Form.',
    ],
    'print applicant application form' => [
        'name' => 'print applicant application form',
        'description' => 'Allows user to print an Applicant Application Form.',
    ],
    'approve applicant application form' => [
        'name' => 'approve applicant application form',
        'description' => 'Allows user to approve an Applicant Application Form.',
    ],
    'generate applicant evaluation report' => [
        'name' => 'generate applicant evaluation report',
        'description' => 'Allows user to generate an evaluation report for an applicant.',
    ],

    // License Renewal Form
    'License Renewal Form' => 'group name',
    'fill license renewal form' => [
        'name' => 'fill license renewal form',
        'description' => 'Allows user to fill out and submit a new License Renewal Form.',
    ],
    'view license renewal form' => [
        'name' => 'view license renewal form',
        'description' => 'Allows user to view submitted License Renewal Forms.',
    ],
    'edit license renewal form' => [
        'name' => 'edit license renewal form',
        'description' => 'Allows user to edit a submitted License Renewal Form.',
    ],
    'delete license renewal form' => [
        'name' => 'delete license renewal form',
        'description' => 'Allows user to delete a License Renewal Form.',
    ],
    'print license renewal form' => [
        'name' => 'print license renewal form',
        'description' => 'Allows user to print a License Renewal Form.',
    ],
    'approve license renewal form' => [
        'name' => 'approve license renewal form',
        'description' => 'Allows user to approve a submitted License Renewal Form.',
    ],

    // SSS Accident Sickness Form
    'SSS Accident Sickness Form' => 'group name',
    'fill SSS accident sickness form' => [
        'name' => 'fill SSS accident sickness form',
        'description' => 'Allows user to fill out and submit a new SSS Accident & Sickness Form.',
    ],
    'delete SSS accident sickness form' => [
        'name' => 'delete SSS accident sickness form',
        'description' => 'Allows user to delete an SSS Accident & Sickness Form.',
    ],
    'edit SSS accident sickness form' => [
        'name' => 'edit SSS accident sickness form',
        'description' => 'Allows user to edit a submitted SSS Accident & Sickness Form.',
    ],
    'view SSS accident sickness form' => [
        'name' => 'view SSS accident sickness form',
        'description' => 'Allows user to view submitted SSS Accident & Sickness Forms.',
    ],
    'print SSS accident sickness form' => [
        'name' => 'print SSS accident sickness form',
        'description' => 'Allows user to print an SSS Accident & Sickness Form.',
    ],
    'approve SSS accident sickness form' => [
        'name' => 'approve SSS accident sickness form',
        'description' => 'Allows user to approve a submitted SSS Accident & Sickness Form.',
    ],
    'generate DOLE report' => [
        'name' => 'generate DOLE report',
        'description' => 'Allows user to generate a DOLE report from the form.',
    ],
    'mark accident sickness form completed' => [
        'name' => 'mark accident sickness form completed',
        'description' => 'Allows user to mark an SSS Accident & Sickness Form as completed.',
    ],

    // Maternity Claim Form
    'Maternity Claim Form' => 'group name',
    'fill maternity claim form' => [
        'name' => 'fill maternity claim form',
        'description' => 'Allows user to fill out and submit a new Maternity Claim Form.',
    ],
    'delete maternity claim form' => [
        'name' => 'delete maternity claim form',
        'description' => 'Allows user to delete a Maternity Claim Form.',
    ],
    'edit maternity claim form' => [
        'name' => 'edit maternity claim form',
        'description' => 'Allows user to edit a submitted Maternity Claim Form.',
    ],
    'view maternity claim form' => [
        'name' => 'view maternity claim form',
        'description' => 'Allows user to view submitted Maternity Claim Forms.',
    ],
    'print maternity claim form' => [
        'name' => 'print maternity claim form',
        'description' => 'Allows user to print a Maternity Claim Form.',
    ],
    'approve maternity claim form' => [
        'name' => 'approve maternity claim form',
        'description' => 'Allows user to approve a submitted Maternity Claim Form.',
    ],
    'generate report' => [
        'name' => 'generate report',
        'description' => 'Allows user to generate a report from the form.',
    ],
    'mark report completed' => [
        'name' => 'mark report completed',
        'description' => 'Allows user to mark a Maternity Claim Form as completed.',
    ],

    // Benevolent Application Form
    'Benevolent Application Form' => 'group name',
    'fill benevolent application form' => [
        'name' => 'fill benevolent application form',
        'description' => 'Allows user to fill out and submit a new Benevolent Application Form.',
    ],
    'delete benevolent application form' => [
        'name' => 'delete benevolent application form',
        'description' => 'Allows user to delete a Benevolent Application Form.',
    ],
    'edit benevolent application form' => [
        'name' => 'edit benevolent application form',
        'description' => 'Allows user to edit a submitted Benevolent Application Form.',
    ],
    'view benevolent application form' => [
        'name' => 'view benevolent application form',
        'description' => 'Allows user to view submitted Benevolent Application Forms.',
    ],
    'print benevolent application form' => [
        'name' => 'print benevolent application form',
        'description' => 'Allows user to print a Benevolent Application Form.',
    ],
    'approve benevolent application form' => [
        'name' => 'approve benevolent application form',
        'description' => 'Allows user to approve a submitted Benevolent Application Form.',
    ],
    'generate benevolent application form report' => [
        'name' => 'generate benevolent application form report',
        'description' => 'Allows user to generate a report from the form.',
    ],
];
