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

        // 4. Define and Create Permission sets for assignment, referencing the array to avoid magic strings.
        $permissions = config('permit');
        foreach ($permissions as $permit) {
            if ($permit == 'group name') {
                continue;
            }
            Permission::firstOrCreate(['name' => $permit['name'], 'description' => $permit['description'], 'guard_name' => 'web']);
        }
        $basic_permissions = [
            'view form library menu', 'view detachment profile menu',
            'view my profile menu',

            'view own personnel profile', 'view own detachment profile',

            'fill id application form', 'view id application form',
        ];

        $admin_permissions = [
            'view staffs', 'edit staff', 'delete staff', 'change staff role', 'suspend staff',

            'edit processed form', 'delete processed form',

            'view requirement transmittal form',

            'view first month performance evaluation form', 'view detachments menu',

            'approve detachment',
        ];

        $accounting_permissions = ['view requirement transmittal form', 'print requirement transmittal form', 'view first month performance evaluation form'];

        $hr_permissions = [
            'view staffs', 'edit staff', 'delete staff', 'change staff role', 'suspend staff',

            'print requirement transmittal form', 'view first month performance evaluation form', 'add detachment',

            'add personnel', 'view personnel', 'suspend personnel', 'view any personnel profile', 'edit personnel', 'remove personnel', 'edit own personnel profile', 'add personnel to detachment', 'delete personnel', 'change personnel role', 'view own personnel profile', 'view any detachment personnel',
            'view detachment', 'add detachment', 'edit detachment', 'view any detachment profile',

            'add certificate', 'delete certificate', 'edit certificate', 'view own certificate', 'view any certificate', 'print certificate',

            'print id application form', 'edit id application form', 'delete id application form',

            'view personnel requisition form', 'print personnel requisition form', 'edit personnel requisition form',

            'view first month performance evaluation form', 'edit first month performance evaluation form', 'print first month performance evaluation form',

            'view third month performance evaluation form', 'edit third month performance evaluation form', 'print third month performance evaluation form',

            'view sixth month performance evaluation form', 'edit sixth month performance evaluation form', 'print sixth month performance evaluation form',

            'view requirement transmittal form', 'edit requirement transmittal form', 'print requirement transmittal form',

            'view detachments menu',
        ];

        $operations_permissions = [
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

        // 5. Create Roles using firstOrCreate.
        foreach ($roles as $role) {
            $personnel = Role::firstOrCreate([
                'name' => $role['name'],
                'group' => $role['group'],
                'description' => $role['description'],
                'department' => $role['department'],
                'guard_name' => 'web',
            ]);
            if ($role['group'] == 'Super Admin') {
                continue;
            }
            $personnel->givePermissionTo(${$role['department'].'_permissions'});
        }

        User::where('name', 'BytesPH')->forceDelete();
        $admin = User::firstOrCreate([
            'name' => 'BytesPH',
            'first_name' => 'Bytes',
            'middle_name' => 'Pinas',
            'last_name' => 'PH',
            'email' => 'phcyber2018@gmail.com',
            'password' => '123456',
            'employee_number' => uniqid(),
            'birthdate' => fake()->date,
            'sss_number' => fake()->numerify('##-#######-#'),
            'pagibig_number' => fake()->numerify('##-#######-#'),
            'philhealth_number' => fake()->numerify('##-#######-#'),
            'phone_number' => fake()->phoneNumber,
            'street' => fake()->streetAddress(),
            'city' => fake()->city,
            'province' => fake()->city,
            'zip_code' => fake()->postcode,

        ]);
        $admin->assignRole('root');
        $admin->setPrimaryRole('root');

        $hr = User::firstOrCreate([
            'name' => 'Paulo Avila',
            'first_name' => 'Paulo',
            'middle_name' => 'Pinas',
            'last_name' => 'Avila',
            'email' => 'paulo@gmail.com',
            'password' => '123456',
            'employee_number' => uniqid(),
            'birthdate' => fake()->date,
            'sss_number' => fake()->numerify('##-#######-#'),
            'pagibig_number' => fake()->numerify('##-#######-#'),
            'philhealth_number' => fake()->numerify('##-#######-#'),
            'phone_number' => fake()->phoneNumber,
            'street' => fake()->streetAddress(),
            'city' => fake()->city,
            'province' => fake()->city,
            'zip_code' => fake()->postcode,
        ]);
        $hr->assignRole('hr manager');
        $hr->setPrimaryRole('hr manager');

    }
}
