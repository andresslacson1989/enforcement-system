<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequirementTransmittalForm extends Model
{
    protected $fillable = [
        'submitted_by',
        'employee_name',
        'employee_id',
        'employee_number',
        'deployment',
        'original_application_form_and_recent_picture',
        'photocopy_application_form_and_recent_picture',
        'sbr',
        'original_security_license',
        'photocopy_security_license',
        'original_nbi_clearance',
        'photocopy_nbi_clearance',
        'original_national_police_clearance',
        'photocopy_national_police_clearance',
        'original_police_clearance',
        'photocopy_police_clearance',
        'original_court_clearance',
        'photocopy_court_clearance',
        'original_barangay_clearance',
        'photocopy_barangay_clearance',
        'original_neuro_psychiatric_test',
        'photocopy_neuro_psychiatric_test',
        'original_drug_test',
        'photocopy_drug_test',
        'diploma_hs',
        'diploma_college',
        'original_diploma',
        'photocopy_diploma',
        'original_transcript',
        'photocopy_transcript',
        'original_training_certificate',
        'photocopy_training_certificate',
        'original_psa_birth',
        'photocopy_psa_birth',
        'original_psa_marriage',
        'photocopy_psa_marriage',
        'original_psa_birth_dependents',
        'photocopy_psa_birth_dependents',
        'original_certificate_of_employment',
        'photocopy_certificate_of_employment',
        'original_community_tax_certificate',
        'photocopy_community_tax_certificate',
        'original_tax_id_number',
        'photocopy_tax_id_number',
        'original_e1_form',
        'photocopy_e1_form',
        'sss_id',
        'sss_slip',
        'original_sss_id',
        'photocopy_sss_id',
        'philhealth_mdr',
        'original_philhealth_id',
        'photocopy_philhealth_id',
        'pagibig_mdf',
        'original_pagibig_id',
        'photocopy_pagibig_id',
        'original_statement_of_account',
        'photocopy_statement_of_account',
        'exp_security_license',
        'exp_nbi_clearance',
        'exp_national_police_clearance',
        'exp_police_clearance',
        'exp_court_clearance',
        'exp_barangay_clearance',
        'exp_neuro_psychiatric_test',
        'exp_drug_test',
        'exp_training_certificate',
        'exp_philhealth_id',
        'exp_pagibig_id',
        'complete_requirements',
        'qualified_for_loan',
        'status',
    ];

    public function submission()
    {
        // This tells Laravel that this form is "submittable"
        return $this->morphOne(Submission::class, 'submittable');
    }

    /**
     * Get the user that owns the form.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
