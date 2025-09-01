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
    Schema::create('activity_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
      $table->string('loggable_type'); // e.g., 'App\Models\Detachment'
      $table->unsignedBigInteger('loggable_id');
      $table->string('action'); // e.g., 'updated', 'created'
      $table->text('message'); // The human-readable message
      $table->timestamps();

      $table->index(['loggable_type', 'loggable_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('activity_logs');
  }
};
