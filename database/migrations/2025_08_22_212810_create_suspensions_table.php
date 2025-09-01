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
        Schema::create('suspensions', function (Blueprint $table) {
            $table->id();
            // This links the suspension to a user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., 'preventive', 'disciplinary'
            $table->text('reason');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            // This logs which admin initiated the suspension
            $table->foreignId('suspended_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            /* // Get a user and all of their suspensions
                $user = User::find(1);
                $suspensions = $user->suspensions; // Returns a collection of Suspension models

                // Get a suspension and find out which user it belongs to
                $suspension = Suspension::find(5);
                $user = $suspension->user; // Returns the User model
                $admin = $suspension->suspendedBy; // Returns the User model of the admin
             */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspensions');
    }
};
