<?php

namespace Database\Seeders;

use App\Models\Detachment;
use App\Models\User;
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
        User::factory(50)->create();

        foreach (User::where('id', '!=', 1)->get() as $item) {
            $item->assignRole('security guard');
            $item->setPrimaryRole('security guard');
        }

        /*        foreach (Detachment::all() as $item) {
                    if ($item->assigned_officer == null) {
                        $user = User::inRandomOrder()->where('id', '!=', 1)->first();
                        $item->update(['assigned_officer' => $user->id]);
                        $user->update(['detachment_id' => $item->id]);
                        $user->assignRole('assigned officer');
                        $user->setPrimaryRole('assigned officer');
                    }
                }*/
    }
}
