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
      Schema::create('requirement_transmittal_forms', function (Blueprint $table) {
        $table->id();
        $table->string('name')->default('Requirement Transmittal Form');
        $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');

        // Remarks
        $table->boolean('complete_requirements')->default(false);
        $table->boolean('qualified_for_loan')->default(false);
        $table->string('denial_reason')->nullable();        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('denied_by')->nullable()->constrained('users')->onDelete('set null');
        $table->dateTime('date_approved')->nullable();
        $table->dateTime('date_denied')->nullable();
        $table->string('status')->default('pending');
        $table->boolean('printed')->default(false);
        $table->boolean('times_printed')->default(false);
        $table->dateTime('date_last_printed')->nullable();
        $table->foreignId('last_printed_by')->nullable()->constrained('users')->onDelete('set null');

        $table->string('employee_name')->nullable();
        $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null'); //employee id is the user id in the database
        $table->string('employee_number')->nullable(); // employee number is the number given by the company
        $table->string('deployment')->nullable();

        // Checkboxes (Boolean fields)
        $table->boolean('original_application_form_and_recent_picture')->default(false);
        $table->boolean('photocopy_application_form_and_recent_picture')->default(false);
        $table->boolean('sbr')->default(false);
        $table->boolean('original_security_license')->default(false);
        $table->boolean('photocopy_security_license')->default(false);
        $table->boolean('original_nbi_clearance')->default(false);
        $table->boolean('photocopy_nbi_clearance')->default(false);
        $table->boolean('original_national_police_clearance')->default(false);
        $table->boolean('photocopy_national_police_clearance')->default(false);
        $table->boolean('original_police_clearance')->default(false);
        $table->boolean('photocopy_police_clearance')->default(false);
        $table->boolean('original_court_clearance')->default(false);
        $table->boolean('photocopy_court_clearance')->default(false);
        $table->boolean('original_barangay_clearance')->default(false);
        $table->boolean('photocopy_barangay_clearance')->default(false);
        $table->boolean('original_neuro_psychiatric_test')->default(false);
        $table->boolean('photocopy_neuro_psychiatric_test')->default(false);
        $table->boolean('original_drug_test')->default(false);
        $table->boolean('photocopy_drug_test')->default(false);
        $table->boolean('diploma_hs')->default(false);
        $table->boolean('diploma_college')->default(false);
        $table->boolean('original_diploma')->default(false);
        $table->boolean('photocopy_diploma')->default(false);
        $table->boolean('original_transcript')->default(false);
        $table->boolean('photocopy_transcript')->default(false);
        $table->boolean('original_training_certificate')->default(false);
        $table->boolean('photocopy_training_certificate')->default(false);
        $table->boolean('original_psa_birth')->default(false);
        $table->boolean('photocopy_psa_birth')->default(false);
        $table->boolean('original_psa_marriage')->default(false);
        $table->boolean('photocopy_psa_marriage')->default(false);
        $table->boolean('original_psa_birth_dependents')->default(false);
        $table->boolean('photocopy_psa_birth_dependents')->default(false);
        $table->boolean('original_certificate_of_employment')->default(false);
        $table->boolean('photocopy_certificate_of_employment')->default(false);
        $table->boolean('original_community_tax_certificate')->default(false);
        $table->boolean('photocopy_community_tax_certificate')->default(false);
        $table->boolean('original_tax_id_number')->default(false);
        $table->boolean('photocopy_tax_id_number')->default(false);
        $table->boolean('original_e1_form')->default(false);
        $table->boolean('photocopy_e1_form')->default(false);
        $table->boolean('sss_id')->default(false);
        $table->boolean('sss_slip')->default(false);
        $table->boolean('original_sss_id')->default(false);
        $table->boolean('photocopy_sss_id')->default(false);
        $table->boolean('philhealth_mdr')->default(false);
        $table->boolean('original_philhealth_id')->default(false);
        $table->boolean('photocopy_philhealth_id')->default(false);
        $table->boolean('pagibig_mdf')->default(false);
        $table->boolean('original_pagibig_id')->default(false);
        $table->boolean('photocopy_pagibig_id')->default(false);
        $table->boolean('original_statement_of_account')->default(false);
        $table->boolean('photocopy_statement_of_account')->default(false);

        // Expiration Dates
        $table->date('exp_security_license')->nullable();
        $table->date('exp_nbi_clearance')->nullable();
        $table->date('exp_national_police_clearance')->nullable();
        $table->date('exp_police_clearance')->nullable();
        $table->date('exp_court_clearance')->nullable();
        $table->date('exp_barangay_clearance')->nullable();
        $table->date('exp_neuro_psychiatric_test')->nullable();
        $table->date('exp_drug_test')->nullable();
        $table->date('exp_training_certificate')->nullable();
        $table->date('exp_philhealth_id')->nullable();
        $table->date('exp_pagibig_id')->nullable();



        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirement_transmittal_forms');
    }
};
