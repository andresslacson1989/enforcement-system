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
            $table->foreignId('detachment_id')->constrained('detachments')->onDelete('cascade');

            // Form specific data
            $table->date('evaluation_date');

            // Performance Ratings (1-5). unsignedTinyInteger is efficient for numbers 0-255.
            $table->unsignedTinyInteger('attendance_and_punctuality');
            $table->unsignedTinyInteger('quality_and_quantity_of_work');
            $table->unsignedTinyInteger('dependability_and_responsibility');
            $table->unsignedTinyInteger('knowledge_of_work');
            $table->unsignedTinyInteger('attitude_and_cooperation');
            $table->unsignedTinyInteger('judgment_and_decision_making');
            $table->unsignedTinyInteger('relationship_with_others');
            $table->unsignedTinyInteger('initiative_and_resourcefulness');
            $table->unsignedTinyInteger('grooming_and_appearance');
            $table->unsignedTinyInteger('physical_condition');
            $table->unsignedTinyInteger('potential_for_growth');
            $table->unsignedTinyInteger('overall_performance_rating');

            // Comments and Recommendations
            $table->text('comments')->nullable();
            $table->text('recommendations')->nullable();

            // Signatures
            $table->string('evaluated_by_name');
            $table->string('evaluated_by_position');
            $table->date('evaluated_by_date');
            $table->string('acknowledged_by_name');
            $table->string('acknowledged_by_position');
            $table->date('acknowledged_by_date');

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
