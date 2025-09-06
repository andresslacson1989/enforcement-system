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
        Schema::create('third_month_performance_evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Third Month Performance Evaluation Form');

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
            $table->foreignId('detachment_id')
                ->constrained('detachments')
                ->onDelete('cascade');

            $table->string('status')->default('submitted');

            // Form specific data
            $table->date('evaluation_date')->nullable();
            $table->date('period_review_start_date')->nullable();
            $table->date('period_review_end_date')->nullable();

            // Detailed Performance Ratings
            $table->string('attendance_punctuality_a')->nullable();
            $table->string('attendance_punctuality_b')->nullable();
            $table->string('attendance_punctuality_c')->nullable();
            $table->string('job_knowledge_a')->nullable();
            $table->string('job_knowledge_b')->nullable();
            $table->string('job_knowledge_c')->nullable();
            $table->string('professionalism_ethic_a')->nullable();
            $table->string('professionalism_ethic_b')->nullable();
            $table->string('professionalism_ethic_c')->nullable();
            $table->string('communication_skills_a')->nullable();
            $table->string('communication_skills_b')->nullable();
            $table->string('communication_skills_c')->nullable();
            $table->string('problem_solving_a')->nullable();
            $table->string('problem_solving_b')->nullable();
            $table->string('problem_solving_c')->nullable();
            $table->string('teamwork_interpersonal_a')->nullable();
            $table->string('teamwork_interpersonal_b')->nullable();
            $table->string('teamwork_interpersonal_c')->nullable();
            $table->string('adaptability_flexibility_a')->nullable();
            $table->string('adaptability_flexibility_b')->nullable();

            // Strengths and Improvements
            $table->string('strength_1')->nullable();
            $table->string('strength_2')->nullable();
            $table->string('strength_3')->nullable();
            $table->string('improvement_1')->nullable();
            $table->string('improvement_2')->nullable();
            $table->string('improvement_3')->nullable();

            // Overall Standing and Comments
            $table->string('overall_standing')->nullable();
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
        Schema::dropIfExists('third_month_performance_evaluation_forms');
    }
};
