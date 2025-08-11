<?php

namespace Database\Seeders;
use App\Models\Detachment;
use App\Models\User;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
    //User::factory(10)->create();

    $this->call([
      PermissionsAndRolesSeeder::class
    ]);

  }
}
