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
        Schema::create('id_application_forms', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default('ID Application Form');
            // Use employee_id for consistency with other forms
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->string('job_title'); // The position for this specific application
            $table->foreignId('submitted_by') // supervisor
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            // Emergency Contact Details
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relation');
            $table->text('emergency_contact_address');
            $table->string('emergency_contact_number');

            $table->string('status')->default('submitted');
            $table->boolean('is_info_complete')->default(false);
            $table->boolean('has_id_picture')->default(false);
            $table->boolean('is_for_filing')->default(false);
            $table->boolean('is_encoded')->default(false);
            $table->boolean('is_card_done')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Corrected table name
        Schema::dropIfExists('id_application_forms');
    }
};
