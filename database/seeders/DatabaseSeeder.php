<?php

namespace Database\Seeders;
use App\Models\Detachment;
use App\Models\User;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\DetachmentFactory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class DatabaseSeeder extends Seeder
{
  use HasRoles;
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory(10)->create();

    $this->call([
      PermissionsAndRolesSeeder::class,
      DetachmentSeeder::class,
    ]);

    $unassigned_users = User::whereDoesntHave('roles')->get();

    foreach ($unassigned_users as $user) {
      $user->assignRole('security guard');
      $user->setPrimaryRole('security guard');
    }
  }
}
