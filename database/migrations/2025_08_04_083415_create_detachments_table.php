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
      Schema::create('detachments', function (Blueprint $table) {
        $table->id();

        // Basic Information
        $table->string('name');
        $table->string('category')->nullable();
        /* Categories
         *  Large Detachment - 61+ guards
            Medium Detachment 21-60 guards
            Small Team 3-20 guards
            Single Post 1-2 guards
         */
        $table->foreignId('assigned_officer')->nullable()->constrained('users')->onDelete('set null');
        $table->string('status')->default('pending'); //statuses: pending, approved
        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        $table->dateTime('approved_at')->nullable();

        // Location Details
        $table->string('street');
        $table->string('city');
        $table->string('province');
        $table->string('zip_code');

        // Duty & Shift Configuration
        $table->integer('hours_per_shift')->nullable();
        $table->integer('max_hrs_duty')->nullable();
        $table->integer('max_ot')->nullable();

        // Pay Rate Configuration
        $table->decimal('hr_rate', 8, 2)->nullable();
        $table->decimal('ot_rate', 8, 2)->nullable();
        $table->decimal('nd_rate', 8, 2)->nullable();
        $table->decimal('rdd_rate', 8, 2)->nullable();
        $table->decimal('rdd_ot_rate', 8, 2)->nullable();
        $table->decimal('hol_rate', 8, 2)->nullable();
        $table->decimal('hol_ot_rate', 8, 2)->nullable();
        $table->decimal('sh_rate', 8, 2)->nullable();
        $table->decimal('sh_ot_rate', 8, 2)->nullable();
        $table->decimal('rd_hol_rate', 8, 2)->nullable();
        $table->decimal('rd_hol_ot_rate', 8, 2)->nullable();
        $table->decimal('rd_sh_rate', 8, 2)->nullable();
        $table->decimal('rd_sh_ot_rate', 8, 2)->nullable();

        // Benefits & Deductions
        $table->decimal('cash_bond', 8, 2)->nullable()->default(200.00);
        $table->decimal('sil', 8, 2)->nullable()->default(0.00);
        $table->decimal('ecola', 8, 2)->nullable()->default(0.00);
        $table->string('retirement_pay')->nullable();
        $table->string('thirteenth_month_pay')->nullable();

        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detachments');
    }
};
