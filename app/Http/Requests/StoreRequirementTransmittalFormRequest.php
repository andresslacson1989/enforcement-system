<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequirementTransmittalFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
      // Spatie: Ensure the user has the necessary permission to submit forms
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      return [
        // Validation rules for checkboxes - all are nullable booleans
        'original_application_form_and_recent_picture' => ['nullable', 'boolean'],
        'photocopy_application_form_and_recent_picture' => ['nullable', 'boolean'],
        'sbr' => ['nullable', 'boolean'],
        'original_security_license' => ['nullable', 'boolean'],
        'photocopy_security_license' => ['nullable', 'boolean'],
        'original_nbi_clearance' => ['nullable', 'boolean'],
        'photocopy_nbi_clearance' => ['nullable', 'boolean'],
        'original_national_police_clearance' => ['nullable', 'boolean'],
        'photocopy_national_police_clearance' => ['nullable', 'boolean'],
        'original_police_clearance' => ['nullable', 'boolean'],
        'photocopy_police_clearance' => ['nullable', 'boolean'],
        'original_court_clearance' => ['nullable', 'boolean'],
        'photocopy_court_clearance' => ['nullable', 'boolean'],
        'original_barangay_clearance' => ['nullable', 'boolean'],
        'photocopy_barangay_clearance' => ['nullable', 'boolean'],
        'original_neuro_psychiatric_test' => ['nullable', 'boolean'],
        'photocopy_neuro_psychiatric_test' => ['nullable', 'boolean'],
        'original_drug_test' => ['nullable', 'boolean'],
        'photocopy_drug_test' => ['nullable', 'boolean'],
        'diploma_hs' => ['nullable', 'boolean'],
        'diploma_college' => ['nullable', 'boolean'],
        'original_diploma' => ['nullable', 'boolean'],
        'photocopy_diploma' => ['nullable', 'boolean'],
        'original_transcript' => ['nullable', 'boolean'],
        'photocopy_transcript' => ['nullable', 'boolean'],
        'original_training_certificate' => ['nullable', 'boolean'],
        'photocopy_training_certificate' => ['nullable', 'boolean'],
        'original_psa_birth' => ['nullable', 'boolean'],
        'photocopy_psa_birth' => ['nullable', 'boolean'],
        'original_psa_marriage' => ['nullable', 'boolean'],
        'photocopy_psa_marriage' => ['nullable', 'boolean'],
        'original_psa_birth_dependents' => ['nullable', 'boolean'],
        'photocopy_psa_birth_dependents' => ['nullable', 'boolean'],
        'original_certificate_of_employment' => ['nullable', 'boolean'],
        'photocopy_certificate_of_employment' => ['nullable', 'boolean'],
        'original_community_tax_certificate' => ['nullable', 'boolean'],
        'photocopy_community_tax_certificate' => ['nullable', 'boolean'],
        'original_tax_id_number' => ['nullable', 'boolean'],
        'photocopy_tax_id_number' => ['nullable', 'boolean'],
        'original_e1_form' => ['nullable', 'boolean'],
        'photocopy_e1_form' => ['nullable', 'boolean'],
        'sss_id' => ['nullable', 'boolean'],
        'sss_slip' => ['nullable', 'boolean'],
        'original_sss_id' => ['nullable', 'boolean'],
        'photocopy_sss_id' => ['nullable', 'boolean'],
        'philhealth_mdr' => ['nullable', 'boolean'],
        'original_philhealth_id' => ['nullable', 'boolean'],
        'photocopy_philhealth_id' => ['nullable', 'boolean'],
        'pagibig_mdf' => ['nullable', 'boolean'],
        'original_pagibig_id' => ['nullable', 'boolean'],
        'photocopy_pagibig_id' => ['nullable', 'boolean'],
        'original_statement_of_account' => ['nullable', 'boolean'],
        'photocopy_statement_of_account' => ['nullable', 'boolean'],
        'complete_requirements' => ['nullable', 'boolean'],
        'qualified_for_loan' => ['nullable', 'boolean'],

        //other
        'denial_reason' => ['nullable', 'string'],

        // Validation rules for expiration dates
        'exp_security_license' => ['nullable', 'date'],
        'exp_nbi_clearance' => ['nullable', 'date'],
        'exp_national_police_clearance' => ['nullable', 'date'],
        'exp_police_clearance' => ['nullable', 'date'],
        'exp_court_clearance' => ['nullable', 'date'],
        'exp_barangay_clearance' => ['nullable', 'date'],
        'exp_neuro_psychiatric_test' => ['nullable', 'date'],
        'exp_drug_test' => ['nullable', 'date'],
        'exp_training_certificate' => ['nullable', 'date'],
        'exp_philhealth_id' => ['nullable', 'date'],
        'exp_pagibig_id' => ['nullable', 'date'],
      ];
    }

  /**
   * Prepare the data for validation.
   * This is useful for handling checkboxes which are not in the request if unchecked.
   */
  protected function prepareForValidation()
  {
    $this->merge([
      'original_application_form_and_recent_picture' => $this->has('original_application_form_and_recent_picture'),
      'photocopy_application_form_and_recent_picture' => $this->has('photocopy_application_form_and_recent_picture'),
      'sbr' => $this->has('sbr'),
      'original_security_license' => $this->has('original_security_license'),
      'photocopy_security_license' => $this->has('photocopy_security_license'),
      'original_nbi_clearance' => $this->has('original_nbi_clearance'),
      'photocopy_nbi_clearance' => $this->has('photocopy_nbi_clearance'),
      'original_national_police_clearance' => $this->has('original_national_police_clearance'),
      'photocopy_national_police_clearance' => $this->has('photocopy_national_police_clearance'),
      'original_police_clearance' => $this->has('original_police_clearance'),
      'photocopy_police_clearance' => $this->has('photocopy_police_clearance'),
      'original_court_clearance' => $this->has('original_court_clearance'),
      'photocopy_court_clearance' => $this->has('photocopy_court_clearance'),
      'original_barangay_clearance' => $this->has('original_barangay_clearance'),
      'photocopy_barangay_clearance' => $this->has('photocopy_barangay_clearance'),
      'original_neuro_psychiatric_test' => $this->has('original_neuro_psychiatric_test'),
      'photocopy_neuro_psychiatric_test' => $this->has('photocopy_neuro_psychiatric_test'),
      'original_drug_test' => $this->has('original_drug_test'),
      'photocopy_drug_test' => $this->has('photocopy_drug_test'),
      'diploma_hs' => $this->has('diploma_hs'),
      'diploma_college' => $this->has('diploma_college'),
      'original_diploma' => $this->has('original_diploma'),
      'photocopy_diploma' => $this->has('photocopy_diploma'),
      'original_transcript' => $this->has('original_transcript'),
      'photocopy_transcript' => $this->has('photocopy_transcript'),
      'original_training_certificate' => $this->has('original_training_certificate'),
      'photocopy_training_certificate' => $this->has('photocopy_training_certificate'),
      'original_psa_birth' => $this->has('original_psa_birth'),
      'photocopy_psa_birth' => $this->has('photocopy_psa_birth'),
      'original_psa_marriage' => $this->has('original_psa_marriage'),
      'photocopy_psa_marriage' => $this->has('photocopy_psa_marriage'),
      'original_psa_birth_dependents' => $this->has('original_psa_birth_dependents'),
      'photocopy_psa_birth_dependents' => $this->has('photocopy_psa_birth_dependents'),
      'original_certificate_of_employment' => $this->has('original_certificate_of_employment'),
      'photocopy_certificate_of_employment' => $this->has('photocopy_certificate_of_employment'),
      'original_community_tax_certificate' => $this->has('original_community_tax_certificate'),
      'photocopy_community_tax_certificate' => $this->has('photocopy_community_tax_certificate'),
      'original_tax_id_number' => $this->has('original_tax_id_number'),
      'photocopy_tax_id_number' => $this->has('photocopy_tax_id_number'),
      'original_e1_form' => $this->has('original_e1_form'),
      'photocopy_e1_form' => $this->has('photocopy_e1_form'),
      'sss_id' => $this->has('sss_id'),
      'sss_slip' => $this->has('sss_slip'),
      'original_sss_id' => $this->has('original_sss_id'),
      'photocopy_sss_id' => $this->has('photocopy_sss_id'),
      'philhealth_mdr' => $this->has('philhealth_mdr'),
      'original_philhealth_id' => $this->has('original_philhealth_id'),
      'photocopy_philhealth_id' => $this->has('photocopy_philhealth_id'),
      'pagibig_mdf' => $this->has('pagibig_mdf'),
      'original_pagibig_id' => $this->has('original_pagibig_id'),
      'photocopy_pagibig_id' => $this->has('photocopy_pagibig_id'),
      'original_statement_of_account' => $this->has('original_statement_of_account'),
      'photocopy_statement_of_account' => $this->has('photocopy_statement_of_account'),
      'complete_requirements' => $this->has('complete_requirements'),
      'qualified_for_loan' => $this->has('qualified_for_loan'),
    ]);
  }
}
