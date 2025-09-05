<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasRoles;

class DatabaseSeeder extends Seeder
{
    use HasRoles;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //   DetachmentSeeder::class,
            PermissionsAndRolesSeeder::class,
            DummyDataSeeder::class,
        ]);

    }
}
