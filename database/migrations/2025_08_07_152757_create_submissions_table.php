<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('submissions', function (Blueprint $table) {
        $table->id();

        // The user who submitted the form. This is a foreign key to the users table.
        // When a user is deleted, all their submissions will also be deleted.
        $table->foreignId('user_id')
          ->constrained()
          ->onDelete('cascade');

        // The polymorphic columns. This will hold the ID and type of the specific form (e.g., RequirementTransmittalForm).
        $table->morphs('submittable');

        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
