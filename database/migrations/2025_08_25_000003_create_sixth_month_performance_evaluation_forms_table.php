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
        Schema::create('sixth_month_performance_evaluation_forms', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default('Sixth Month Performance Evaluation Form');
            // Foreign keys for relationships
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('submitted_by') // supervisor
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->string('employee_number')->nullable();
            $table->string('job_title')->nullable();
            $table->foreignId('detachment_id')->constrained('detachments')->onDelete('cascade');
            $table->string('status')->default('pending');

            // Performance Criteria Ratings
            $table->string('attendance_punctuality_a');
            $table->string('attendance_punctuality_b');
            $table->string('attendance_punctuality_c');
            $table->string('job_knowledge_a');
            $table->string('job_knowledge_b');
            $table->string('job_knowledge_c');
            $table->string('professionalism_ethic_a');
            $table->string('professionalism_ethic_b');
            $table->string('professionalism_ethic_c');
            $table->string('communication_skills_a');
            $table->string('communication_skills_b');
            $table->string('communication_skills_c');
            $table->string('problem_solving_a');
            $table->string('problem_solving_b');
            $table->string('problem_solving_c');
            $table->string('teamwork_interpersonal_a');
            $table->string('teamwork_interpersonal_b');
            $table->string('teamwork_interpersonal_c');
            $table->string('adaptability_flexibility_a');
            $table->string('adaptability_flexibility_b');

            // Strengths and Improvements
            $table->text('strength_1')->nullable();
            $table->text('strength_2')->nullable();
            $table->text('strength_3')->nullable();
            $table->text('improvement_1')->nullable();
            $table->text('improvement_2')->nullable();
            $table->text('improvement_3')->nullable();

            // Overall Standing
            $table->string('overall_standing');

            // Comments
            $table->text('supervisor_comment')->nullable();
            $table->text('security_comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sixth_month_performance_evaluation_forms');
    }
};
