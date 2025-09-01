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
        Schema::create('first_month_performance_evaluation_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('First Month Performance Evaluation Form');

            // Employee and Supervisor Info
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

            // actions
            $table->dateTime('meeting_date')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('printed')->default(false);
            $table->boolean('times_printed')->default(false);
            $table->dateTime('date_last_printed')->nullable();
            $table->foreignId('last_printed_by')->nullable()->constrained('users')->onDelete('set null');

            // Performance Criteria (now single string columns)
            $table->string('knowledge_understanding_a')->nullable();
            $table->string('knowledge_understanding_b')->nullable();
            $table->string('attendance_a')->nullable();
            $table->string('attendance_b')->nullable();
            $table->string('observation_a')->nullable();
            $table->string('observation_b')->nullable();
            $table->string('communication_a')->nullable();
            $table->string('communication_b')->nullable();
            $table->string('professionalism_a')->nullable();
            $table->string('professionalism_b')->nullable();

            // Areas of Strength and Improvement
            $table->text('strength_1')->nullable();
            $table->text('strength_2')->nullable();
            $table->text('strength_3')->nullable();
            $table->text('improvement_1')->nullable();
            $table->text('improvement_2')->nullable();
            $table->text('improvement_3')->nullable();

            // Overall Standing
            $table->string('overall_standing')->nullable();

            // Comments and Signatures
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
        Schema::dropIfExists('first_month_performance_evaluation_forms');
    }
};
