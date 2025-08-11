<?php

namespace Database\Seeders;

use App\Models\Detachment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      //Create Permission
      //Staff Permissions
      $add_staff = Permission::create(['name' => 'add staff', 'group' => 'Staff']);
      $delete_staff = Permission::create(['name' => 'delete staff', 'group' => 'Staff']);
      $edit_staff = Permission::create(['name' => 'edit staff', 'group' => 'Staff']);
      $view_staff = Permission::create(['name' => 'view staff', 'group' => 'Staff']);

      //Guard Permissions
      $add_guard = Permission::create(['name' => 'add guard', 'group' => 'Guard']);
      $delete_guard = Permission::create(['name' => 'delete guard', 'group' => 'Guard']);
      $edit_guard = Permission::create(['name' => 'edit guard', 'group' => 'Guard']);
      $view_guard = Permission::create(['name' => 'view guard', 'group' => 'Guard']);

      //Role Permissions
      $add_role = Permission::create(['name' => 'add role', 'group' => 'Role']);
      $delete_role = Permission::create(['name' => 'delete role', 'group' => 'Role']);
      $edit_role = Permission::create(['name' => 'edit role', 'group' => 'Role']);
      $view_role = Permission::create(['name' => 'view role', 'group' => 'Role']);

      //Permission Permissions
      $add_permission = Permission::create(['name' => 'add permission', 'group' => 'Permission']);
      $delete_permission = Permission::create(['name' => 'delete permission', 'group' => 'Permission']);
      $edit_permission = Permission::create(['name' => 'edit permission', 'group' => 'Permission']);
      $view_permission = Permission::create(['name' => 'view permission', 'group' => 'Permission']);

      //Attendance Permissions
      $input_attendance = Permission::create(['name' => 'input attendance', 'group' => 'Attendance']);
      $edit_attendance = Permission::create(['name' => 'edit attendance', 'group' => 'Attendance']);
      $view_attendance = Permission::create(['name' => 'view attendance', 'group' => 'Attendance']);
      $delete_attendance = Permission::create(['name' => 'delete attendance', 'group' => 'Attendance']);
      $confirm_attendance = Permission::create(['name' => 'confirm attendance', 'group' => 'Attendance']);
      $generate_payslip = Permission::create(['name' => 'generate payslip', 'group' => 'Attendance']);
      $delete_payslip = Permission::create(['name' => 'delete payslip', 'group' => 'Attendance']);
      $edit_payslip = Permission::create(['name' => 'edit payslip', 'group' => 'Attendance']);
      $view_payslip = Permission::create(['name' => 'view payslip', 'group' => 'Attendance']);
      $print_payslip = Permission::create(['name' => 'print payslip', 'group' => 'Attendance']);

      //Forms Permission
      $edit_approved_forms = Permission::create(['name' => 'edit approved forms', 'group' => 'Approved Forms']);
      $delete_approved_forms = Permission::create(['name' => 'edit delete forms', 'group' => 'Approved Forms']);
      $view_approved_forms = Permission::create(['name' => 'edit view forms', 'group' => 'Approved Forms']);
      $print_approved_forms = Permission::create(['name' => 'edit print forms', 'group' => 'Approved Forms']);

      //HR Permissions
      //Leave Application Form
      $fill_leave_application_form = Permission::create(['name' => 'fill leave application form', 'group' => 'Leave Application Form']);
      $approve_leave_application_form = Permission::create(['name' => ' approve application form', 'group' => 'Leave Application Form']);
      $delete_leave_application_form = Permission::create(['name' => 'delete leave application form', 'group' => 'Leave Application Form']);
      $edit_leave_application_form = Permission::create(['name' => 'edit leave application form', 'group' => 'Leave Application Form']);
      $view_leave_application_form = Permission::create(['name' => 'view leave application form', 'group' => 'Leave Application Form']);
      $print_leave_application_form = Permission::create(['name' => 'print leave application form', 'group' => 'Leave Application Form']);
      $process_leave_application_form = Permission::create(['name' => 'process leave application form', 'group' => 'Leave Application Form']);

      //Requirement Transmittal Form
      $fill_requirement_transmittal_form = Permission::create(['name' => 'fill requirement transmittal form', 'group' => 'Requirement Transmittal Form']);
      $delete_requirement_transmittal_form = Permission::create(['name' => 'delete requirement transmittal form', 'group' => 'Requirement Transmittal Form']);
      $edit_requirement_transmittal_form = Permission::create(['name' => 'edit requirement transmittal form', 'group' => 'Requirement Transmittal Form']);
      $view_requirement_transmittal_form = Permission::create(['name' => 'view requirement transmittal form', 'group' => 'Requirement Transmittal Form']);
      $approve_requirement_transmittal_form = Permission::create(['name' => 'approve requirement transmittal form', 'group' => 'Requirement Transmittal Form']);
      $print_requirement_transmittal_form = Permission::create(['name' => 'print requirement transmittal form', 'group' => 'Requirement Transmittal Form']);
      $sign_requirement_transmittal_form = Permission::create(['name' => 'sign requirement transmittal form', 'group' => 'Requirement Transmittal Form']);

      //First Month Performance Evaluation Form
      $fill_first_month_performance_evaluation_form = Permission::create(['name' => 'fill first month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $schedule_first_month_performance_evaluation = Permission::create(['name' => 'schedule first month performance evaluation', 'group' => 'Performance Evaluation Form']);
      $delete_first_month_performance_evaluation_form = Permission::create(['name' => 'delete first month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $view_first_month_performance_evaluation_form = Permission::create(['name' => 'view first month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $edit_first_month_performance_evaluation_form = Permission::create(['name' => 'edit first month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $approve_first_month_performance_evaluation_form = Permission::create(['name' => 'approve first month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $sign_first_month_performance_evaluation_form = Permission::create(['name' => 'sign first month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $print_first_month_performance_evaluation_form = Permission::create(['name' => 'print first month performance evaluation form', 'group' => 'Performance Evaluation Form']);

      //Third Month Performance Evaluation Form
      $fill_third_month_performance_evaluation_form = Permission::create(['name' => 'fill third month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $schedule_third_month_performance_evaluation = Permission::create(['name' => 'schedule third month performance evaluation', 'group' => 'Performance Evaluation Form']);
      $delete_third_month_performance_evaluation_form = Permission::create(['name' => 'delete third month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $view_third_month_performance_evaluation_form = Permission::create(['name' => 'view third month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $edit_third_month_performance_evaluation_form = Permission::create(['name' => 'edit third month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $approve_third_month_performance_evaluation_form = Permission::create(['name' => 'approve third month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $sign_third_month_performance_evaluation_form = Permission::create(['name' => 'sign third month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $print_third_month_performance_evaluation_form = Permission::create(['name' => 'print third month performance evaluation form', 'group' => 'Performance Evaluation Form']);

      //Sixth Month Performance Evaluation Form
      $fill_sixth_month_performance_evaluation_form = Permission::create(['name' => 'fill sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $schedule_sixth_month_performance_evaluation = Permission::create(['name' => 'schedule sixth month performance evaluation', 'group' => 'Performance Evaluation Form']);
      $delete_sixth_month_performance_evaluation_form = Permission::create(['name' => 'delete sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $view_sixth_month_performance_evaluation_form = Permission::create(['name' => 'view sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $edit_sixth_month_performance_evaluation_form = Permission::create(['name' => 'edit sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $approve_sixth_month_performance_evaluation_form = Permission::create(['name' => 'approve sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $sign_sixth_month_performance_evaluation_form = Permission::create(['name' => 'sign sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);
      $print_sixth_month_performance_evaluation_form = Permission::create(['name' => 'print sixth month performance evaluation form', 'group' => 'Performance Evaluation Form']);

      //ID Application Form
      $fill_id_application_form = Permission::create(['name' => 'fill ID application form', 'group' => 'ID Application Form']);
      $delete_id_application_form = Permission::create(['name' => 'delete ID application form', 'group' => 'ID Application Form']);
      $edit_id_application_form = Permission::create(['name' => 'edit ID application form', 'group' => 'ID Application Form']);
      $view_id_application_form = Permission::create(['name' => 'view ID application form', 'group' => 'ID Application Form']);
      $print_id_application_form = Permission::create(['name' => 'print ID application form', 'group' => 'ID Application Form']);

      //Personnel Requisition Form
      $fill_personnel_requisition_form = Permission::create(['name' => 'fill Personnel requisition form', 'group' => 'Personnel requisition form']);
      $delete_personnel_requisition_form = Permission::create(['name' => 'delete Personnel requisition form', 'group' => 'Personnel requisition form']);
      $edit_personnel_requisition_form = Permission::create(['name' => 'edit Personnel requisition form', 'group' => 'Personnel requisition form']);
      $view_personnel_requisition_form = Permission::create(['name' => 'View personnel requisition form', 'group' => 'Personnel requisition form']);
      $print_personnel_requisition_form = Permission::create(['name' => 'print personnel requisition form', 'group' => 'Personnel requisition form']);

      //Supply Requisition Form
      $fill_supply_requisition_form = Permission::create(['name' => 'fill supply requisition form', 'group' => 'Supply Requisition Form']);
      $delete_supply_requisition_form = Permission::create(['name' => 'delete supply requisition form', 'group' => 'Supply Requisition Form']);
      $edit_supply_requisition_form = Permission::create(['name' => 'edit supply requisition form', 'group' => 'Supply Requisition Form']);
      $view_supply_requisition_form = Permission::create(['name' => 'view supply requisition form', 'group' => 'Supply Requisition Form']);
      $approve_supply_requisition_form = Permission::create(['name' => 'approve supply requisition form', 'group' => 'Supply Requisition Form']);
      $print_supply_requisition_form = Permission::create(['name' => 'print supply requisition form', 'group' => 'Supply Requisition Form']);
      $acknowledge_receipt = Permission::create(['name' => 'acknowledge receipt', 'group' => 'Supply Requisition Form']);

      //Applicant Evaluation Form
      $fill_applicant_application_form = Permission::create(['name' => 'fill applicant application form', 'group' => 'Applicant Application Form']);
      $delete_applicant_application_form = Permission::create(['name' => 'delete applicant application form', 'group' => 'Applicant Application Form']);
      $view_applicant_application_form = Permission::create(['name' => 'view applicant application form', 'group' => 'Applicant Application Form']);
      $edit_applicant_application_form = Permission::create(['name' => 'edit applicant application form', 'group' => 'Applicant Application Form']);
      $approve_applicant_application_form = Permission::create(['name' => 'approve applicant application form', 'group' => 'Applicant Application Form']);
      $print_applicant_application_form = Permission::create(['name' => 'print applicant application form', 'group' => 'Applicant Application Form']);
      $generate_applicant_evaluation_report = Permission::create(['name' => 'generate applicant evaluation report', 'group' => 'Applicant Application Form']);

      //License Renewal Form ????
      $fill_license_renewal_form = Permission::create(['name' => 'fill license renewal form', 'group' => 'License Renewal Form']);
      $view_license_renewal_form = Permission::create(['name' => 'view license renewal form', 'group' => 'License Renewal Form']);
      $edit_license_renewal_form = Permission::create(['name' => 'edit license renewal form', 'group' => 'License Renewal Form']);
      $delete_license_renewal_form = Permission::create(['name' => 'delete license renewal form', 'group' => 'License Renewal Form']);
      $print_license_renewal_form = Permission::create(['name' => 'print license renewal form', 'group' => 'License Renewal Form']);
      $approve_license_renewal_form = Permission::create(['name' => 'approve license renewal form', 'group' => 'License Renewal Form']);

      //SSS Accident Sickness Form
      $fill_SSS_accident_sickness_form = Permission::create(['name' => 'fill SSS accident sickness form', 'group' => 'SSS Accident Sickness Form']);
      $delete_SSS_accident_sickness_form = Permission::create(['name' => 'delete SSS accident sickness form', 'group' => 'SSS Accident Sickness Form']);
      $edit_SSS_accident_sickness_form = Permission::create(['name' => 'edit SSS accident sickness form', 'group' => 'SSS Accident Sickness Form']);
      $view_SSS_accident_sickness_form = Permission::create(['name' => 'view SSS accident sickness form', 'group' => 'SSS Accident Sickness Form']);
      $print_SSS_accident_sickness_form = Permission::create(['name' => 'print SSS accident sickness form', 'group' => 'SSS Accident Sickness Form']);
      $approve_SSS_accident_sickness_form = Permission::create(['name' => 'approve SSS accident sickness form', 'group' => 'SSS Accident Sickness Form']);
      $generate_DOLE_report = Permission::create(['name' => 'generate DOLE report', 'group' => 'SSS Accident Sickness Form']);
      $mark_accident_sickness_form_completed = Permission::create(['name' => 'mark accident sickness form completed', 'group' => 'SSS Accident Sickness Form']);

      //Maternity Claim Form
      $fill_maternity_claim_form = Permission::create(['name' => 'fill maternity claim form', 'group' => 'Maternity Claim Form']);
      $delete_maternity_claim_form = Permission::create(['name' => 'delete material claim form', 'group' => 'Maternity Claim Form']);
      $edit_maternity_claim_form = Permission::create(['name' => 'edit maternity claim form', 'group' => 'Maternity Claim Form']);
      $view_maternity_claim_form = Permission::create(['name' => 'view material claim form', 'group' => 'Maternity Claim Form']);
      $print_maternity_claim_form = Permission::create(['name' => 'print material claim form', 'group' => 'Maternity Claim Form']);
      $approve_maternity_claim_form = Permission::create(['name' => 'approve material claim form', 'group' => 'Maternity Claim Form']);
      $generate_maternity_claim_report = Permission::create(['name' => 'generate report', 'group' => 'Maternity Claim Form']);
      $mark_maternity_claim_form_completed = Permission::create(['name' => 'mark report completed', 'group' => 'Maternity Claim Form']);

      //Benevolent Application Form
      $fill_benevolent_application_form = Permission::create(['name' => 'fill benevolent application form', 'group' => 'Benevolent Application Form']);
      $delete_benevolent_application_form = Permission::create(['name' => 'delete benevolent application form', 'group' => 'Benevolent Application Form']);
      $edit_benevolent_application_form = Permission::create(['name' => 'edit benevolent application form', 'group' => 'Benevolent Application Form']);
      $view_benevolent_application_form = Permission::create(['name' => 'view benevolent application form', 'group' => 'Benevolent Application Form']);
      $print_benevolent_application_form = Permission::create(['name' => 'print benevolent application form', 'group' => 'Benevolent Application Form']);
      $approve_benevolent_application_form = Permission::create(['name' => 'approve benevolent application form', 'group' => 'Benevolent Application Form']);
      $generate_benevolent_application_form_report = Permission::create(['name' => 'generate benevolent application form report', 'group' => 'Benevolent Application Form']);

      // $president  Permission
      $update_processed_form = Permission::create(['name' => 'update processed form', 'group' => 'Processed Form']);
      $delete_processed_form = Permission::create(['name' => 'delete processed form', 'group' => 'Processed Form']);


      // Create Roles
      $root = Role::create(['name' => 'root', 'description' => 'Super Admin Access']); // superadmin

      //Executives have only one account cannot be created withing the system for security reasons
      $president = Role::create(['name' => 'president', 'description' => 'Admin Access']);
      $vice_president = Role::create(['name' => 'vice president', 'description' => 'Admin Access']);
      $general_manager = Role::create(['name' => 'general manager', 'description' => 'General Manager']);

      //Staffs
      $accounting_manager = Role::create(['name' => 'accounting manager', 'description' => 'Accounting Manager']);
      $senior_accounting_manager = Role::create(['name' => 'senior accounting manager', 'description' => 'Senior Accounting Manager']);
      $accounting_specialist = Role::create(['name' => 'accounting specialist', 'description' => 'Accounting Specialist']);
      $hr_manager = Role::create(['name' => 'hr manager', 'description' => 'HR Manager']);
      $hr_specialist = Role::create(['name' => 'hr specialist', 'description' => 'HR Specialist']);
      $operation_manager = Role::create(['name' => 'operation manager', 'description' => 'Operation Manager']);

      //Guard
      $detachment_commander = Role::create(['name' => 'detachment commander', 'description' => 'Detach Commander']);
      $security_officer = Role::create(['name' => 'security officer', 'description' => 'Security Officer']);
      $head_guard = Role::create(['name' => 'head guard', 'description' => 'Head Guard']);
      $security_guard = Role::create(['name' => 'security guard', 'description' => 'Security Guard']);

      //Give Permissions
      $pres_perm = [
        'update processed form',
        'delete processed form',
        'view first month performance evaluation form',
      ];

      $vice_pres_perm = [
        'update processed form',
        'delete processed form',
        'view first month performance evaluation form',
      ];

      $gen_manager_perm = [
        'update processed form',
        'delete processed form',
        'view first month performance evaluation form',
      ];

      $accounting_perm = [
        'view requirement transmittal form',
        'print requirement transmittal form',
        'view first month performance evaluation form',
      ];

      $hr_perm = [
        'fill requirement transmittal form',
        'view requirement transmittal form',
        'edit requirement transmittal form',
        'approve requirement transmittal form',
        'print requirement transmittal form',
        'view first month performance evaluation form',
      ];

      $operation_manager_perm = [
        'view first month performance evaluation form',
        'fill first month performance evaluation form',
        'edit first month performance evaluation form'
      ];

      $detachment_commander_perm = [
        'view first month performance evaluation form',
        'fill first month performance evaluation form',
        'edit first month performance evaluation form'
      ];

      $head_guard_perm = [
        'view first month performance evaluation form',
        'fill first month performance evaluation form',
        'edit first month performance evaluation form'
      ];

      $security_officer_perm = [
        'view first month performance evaluation form',
        'fill first month performance evaluation form',
        'edit first month performance evaluation form'
      ];

      $security_guard_perm = [
        'view requirement transmittal form',
        'fill requirement transmittal form',
        'view first month performance evaluation form'
      ];



      //President
      $president->givePermissionTo($pres_perm);

      //Vice President
      $vice_president->givePermissionTo($vice_pres_perm);

      //General Manager
      $general_manager->givePermissionTo($gen_manager_perm);

      //Accounting Manager
      $accounting_manager->givePermissionTo($accounting_perm);

      //Accounting Senior Manager
      $senior_accounting_manager->givePermissionTo($accounting_perm);

      //Accounting Specialist
      $accounting_specialist->givePermissionTo($accounting_perm);

      //HR Manager
      $hr_manager->givePermissionTo($hr_perm);

      //HR Specialist
      $hr_specialist->givePermissionTo($hr_perm);

      //Operation Manager
      $operation_manager->givePermissionTo($operation_manager_perm);

      //Detachment Commander
      $detachment_commander->givePermissionTo($detachment_commander_perm);

      //Head Guard
      $head_guard->givePermissionTo($head_guard_perm);

      //Security Office
      $security_officer->givePermissionTo($security_officer_perm);

      //Security Guard
      $security_guard->givePermissionTo($security_guard_perm);



      $detachment = Detachment::create([
        'name' => 'Tarlac Detachment',
        'address' => 'Sto Domingo 2nd Capas, Tarlac',
      ]);

      $detachment2 = Detachment::create([
        'name' => 'Capas Detachment',
        'address' => 'Sto Domingo 2nd Capas, Tarlac',
      ]);

      $admin = User::factory()->create([
        'name' => 'BytesPH',
        'first_name' => 'BytesPH',
        'middle_name' => 'BytesPH',
        'last_name' => 'BytesPH',
        'suffix' => 'BytesPH',
        'email' => 'phcyber2018@gmail.com',
        'password' => '123456',
        'detachment_id' => $detachment->id,
      ]);

      $guard = User::factory()->create([
        'name' => 'juan',
        'first_name' => 'Juan',
        'middle_name' => 'Pinas',
        'last_name' => 'Dela Cruz',
        'suffix' => 'III',
        'email' => 'guard1@gmail.com',
        'password' => '123456',
        'employee_number' => uniqid(),
        'detachment_id' => $detachment->id,
      ]);

      $guard2 = User::factory()->create([
        'name' => 'Ramon',
        'first_name' => 'Ramon',
        'middle_name' => 'Pinas',
        'last_name' => 'Pancho',
        'suffix' => 'III',
        'email' => 'guard2@gmail.com',
        'password' => '123456',
        'employee_number' => uniqid(),
        'detachment_id' => $detachment2->id,
      ]);

      $guard3 = User::factory()->create([
        'name' => 'Cesar',
        'first_name' => 'Cesar',
        'middle_name' => 'Pinas',
        'last_name' => 'Montano',
        'suffix' => 'III',
        'email' => 'guard3@gmail.com',
        'password' => '123456',
        'employee_number' => uniqid(),
        'detachment_id' => $detachment2->id,
      ]);

      $hr1 = User::factory()->create([
        'name' => 'hr1',
        'first_name' => 'Pedro',
        'middle_name' => 'Pinas',
        'last_name' => 'Penduko',
        'suffix' => 'Jr',
        'email' => 'hr1@gmail.com',
        'password' => '123456',
        'employee_number' => uniqid(),
        'detachment_id' => $detachment->id,
      ]);

      $accounting1 = User::factory()->create([
        'name' => 'accounting1',
        'first_name' => 'Maria',
        'middle_name' => 'Pinas',
        'last_name' => 'Makiling',
        'email' => 'accounting1@gmail.com',
        'password' => '123456',
        'employee_number' => uniqid(),
        'detachment_id' => $detachment->id,
      ]);

      $dc1 = User::factory()->create([
        'name' => 'cd1',
        'first_name' => 'Mario',
        'middle_name' => 'Pinas',
        'last_name' => 'Kundiman',
        'email' => 'dc1@gmail.com',
        'password' => '123456',
        'employee_number' => uniqid(),
      ]);

      $guard->assignRole('security guard');
      $guard2->assignRole('security guard');
      $guard3->assignRole('head guard');
      $hr1->assignRole('hr manager');
      $accounting1->assignRole('accounting manager');
      $dc1->assignRole('detachment commander');

      $detachment->commander = $admin->id;
      $detachment->save();

      $detachment2->commander = $guard3->id;
      $detachment2->save();

      $admin->assignRole('root');
    }
}
