<?php

namespace Database\Seeders;

use App\Models\Detachment;
use Illuminate\Database\Seeder; // 1. Import your Detachment model

class DetachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Call the factory to create 25 demo detachments
        Detachment::factory()->count(5)->create();
    }
}
