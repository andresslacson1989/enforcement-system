<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * This seeder is designed to be run multiple times without causing errors.
     * It uses firstOrCreate() to prevent creating duplicate permissions and roles.
     *
     * To run this seeder: php artisan db:seed --class=PermissionsAndRolesSeeder
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions. This is important for development.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define all permissions in a structured array by group for easy management.
        $permissions = config('permit');

        $roles = config('roles');
        foreach ($permissions as $key => $permission) {
            if ($permission == 'group name') {
                $name = $key;

                continue;
            }
            Permission::firstOrCreate(['name' => $permission['name'], 'group' => $name, 'description' => $permission['description'], 'guard_name' => 'web']);
        }

        // 4. Create Roles using firstOrCreate.
        foreach ($roles as $key => $role) {
            foreach ($role as $item) {
                Role::firstOrCreate(['name' => $item['name'], 'group' => $key, 'description' => $item['description'], 'guard_name' => 'web']);
            }
        }
        $root = Role::firstOrCreate(['name' => 'root'], ['description' => 'Super Admin Access']);

        $president = Role::firstOrCreate(['name' => 'president'], ['description' => 'Admin Access', 'group' => 'admin']);
        $vice_president = Role::firstOrCreate(['name' => 'vice president'], ['description' => 'Admin Access', 'group' => 'admin']);
        $general_manager = Role::firstOrCreate(['name' => 'general manager'], ['description' => 'General Manager', 'group' => 'admin']);

        $accounting_manager = Role::firstOrCreate(['name' => 'accounting manager'], ['description' => 'Accounting Manager', 'group' => 'staff']);
        $senior_accounting_manager = Role::firstOrCreate(['name' => 'senior accounting manager'], ['description' => 'Senior Accounting Manager', 'group' => 'staff']);
        $accounting_specialist = Role::firstOrCreate(['name' => 'accounting specialist'], ['description' => 'Accounting Specialist', 'group' => 'staff']);
        $hr_manager = Role::firstOrCreate(['name' => 'hr manager'], ['description' => 'HR Manager', 'group' => 'staff']);
        $hr_specialist = Role::firstOrCreate(['name' => 'hr specialist'], ['description' => 'HR Specialist', 'group' => 'staff']);
        $operation_manager = Role::firstOrCreate(['name' => 'operation manager'], ['description' => 'Overall management', 'group' => 'staff']);

        $assigned_officer = Role::firstOrCreate(['name' => 'assigned officer'], ['description' => 'Head of Detachment', 'group' => 'personnel-admin']);
        $detachment_commander = Role::firstOrCreate(['name' => 'detachment commander'], ['description' => 'Overall in charge', 'group' => 'personnel-admin']);
        $officer_in_charge = Role::firstOrCreate(['name' => 'officer in charge'], ['description' => 'OIC in absence of Commander', 'group' => 'personnel-admin']);
        $security_in_charge = Role::firstOrCreate(['name' => 'security in charge'], ['description' => 'Second in command', 'group' => 'personnel-admin']);

        $cluster_head_guard = Role::firstOrCreate(['name' => 'cluster head guard'], ['description' => 'Supervises a cluster', 'group' => 'personnel-base']);
        $head_guard = Role::firstOrCreate(['name' => 'head guard'], ['description' => 'In charge of the small team', 'group' => 'personnel-base']);
        $assistant_head_guard = Role::firstOrCreate(['name' => 'assistant head guard'], ['description' => 'Assists the Head Guard', 'group' => 'personnel-base']);
        $security_guard = Role::firstOrCreate(['name' => 'security guard'], ['description' => 'The main security force (male)', 'group' => 'personnel-base']);
        $lady_guard = Role::firstOrCreate(['name' => 'lady guard'], ['description' => 'The main security force (female)', 'group' => 'personnel-base']);

        // 5. Define Permission sets for assignment, referencing the array to avoid magic strings.
        $basic_permissions = [
            'view form library menu', 'view detachment profile menu',
            'view my profile menu',

            'view own personnel profile', 'view own detachment profile',
        ];

        $admin_permissions = [
            'view staffs', 'edit staff', 'delete staff', 'change staff role', 'suspend staff',

            'update processed form', 'delete processed form',

            'view requirement transmittal form',

            'view first month performance evaluation form', 'view detachments menu',

            'approve detachment',
        ];

        $accounting_permissions = ['view requirement transmittal form', 'print requirement transmittal form', 'view first month performance evaluation form'];

        $hr_permissions = [
            'view staffs', 'edit staff', 'delete staff', 'change staff role', 'suspend staff',

            'fill requirement transmittal form', 'view requirement transmittal form', 'edit requirement transmittal form',
            'print requirement transmittal form', 'view first month performance evaluation form', 'add detachment',
            'view detachments menu',
        ];

        $operation_permissions = [
            'view requirement transmittal form', 'view first month performance evaluation form',
            'fill first month performance evaluation form', 'edit first month performance evaluation form',
        ];

        $personnel_admin_permissions = [
            'view personnel',
            'edit personnel',
            'suspend personnel',
            'view detachment',
            'view own detachment personnel',
            'view own detachment personnel profile',

            'add personnel to detachment',
            'edit detachment',
            'view requirement transmittal form',

            'view first month performance evaluation form',
            'edit first month performance evaluation form',
            'fill first month performance evaluation form',
            'print first month performance evaluation form',
        ];

        $personnel_base_permissions = [
            'view requirement transmittal form',
            'view first month performance evaluation form',
        ];

        // 6. Assign Permissions to Roles in a structured way.
        $allRoles = Role::where('name', '!=', 'root')->get();
        foreach ($allRoles as $role) {
            $role->givePermissionTo($basic_permissions);
        }

        $president->givePermissionTo($admin_permissions);
        $vice_president->givePermissionTo($admin_permissions);
        $general_manager->givePermissionTo($admin_permissions);

        foreach ([$accounting_manager, $senior_accounting_manager, $accounting_specialist] as $role) {
            $role->givePermissionTo($accounting_permissions);
        }

        foreach ([$hr_manager, $hr_specialist] as $role) {
            $role->givePermissionTo($hr_permissions);
        }

        $operation_manager->givePermissionTo($operation_permissions);

        foreach ([$assigned_officer, $detachment_commander, $officer_in_charge, $security_in_charge] as $role) {
            $role->givePermissionTo($personnel_admin_permissions);
        }

        foreach ([$cluster_head_guard, $head_guard, $assistant_head_guard, $security_guard, $lady_guard] as $role) {
            $role->givePermissionTo($personnel_base_permissions);
        }

        User::where('name', 'BytesPH')->forceDelete();
        $admin = User::firstOrCreate([
            'id' => 1,
            'name' => 'BytesPH',
            'first_name' => 'BytesPH',
            'middle_name' => 'BytesPH',
            'last_name' => 'BytesPH',
            'suffix' => 'BytesPH',
            'email' => 'phcyber2018@gmail.com',
            'password' => '123456',
            'employee_number' => uniqid(),
        ]);
        $admin->assignRole('root');
        $admin->setPrimaryRole('root');

    }
}
