@php use Carbon\Carbon;use Illuminate\Support\Facades\Auth; @endphp
<div class="row d-flex justify-content-around">
    @include('content.snippets.enforcement_header')
    <div class="col">
        <h5 class="mb-4">Employee Info</h5>
    </div>
    <div class="col">
        <!-- Display the submission date -->
        <h5 class="mb-4 text-end">{{ Carbon::parse($submission->created_at)->format('F d, Y') }}</h5>
    </div>
</div>
<div class="row g-6">
    <div class="col-md-12">
        <label class="form-label" for="employee_name">Full Name: </label> <span class="fw-bold">{{ $employee->first_name ?? '' }} {{ $employee->middle_name ?? '' }} {{ $employee->last_name ?? '' }} {{ $employee->suffix ?? '' }}</span>
        <br>
        <label class="form-label" for="employee_number">Employee No: </label> <span class="fw-bold">{{ $employee->employee_number ?? '' }}</span> <br>
        <label class="form-label" for="deployment">Deployment: </label> <span class="fw-bold">{{ $employee->detachment->name ?? '' }} <small class="text-muted">[{{ $detachment->address ?? '' }}]</small></span>
    </div>
    <div class="col-md-6">
    </div>
</div>
<div class="row g-6">
    <div class="col-md-12 mb-5">
        <table class="table table-borderless table-sm table-flush-spacing">
            <thead>
            <tr class="text-center">
                <th>Requirements</th>
                <th>Expiration</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <span class="fw-bold mb">1. Application Form with 2 pcs. 2x2 recent picture (white background)</span>
                    <div class="mt-2">
                        <input class="form-check-input ms-5" type="checkbox" id="original_application_form_and_recent_picture" name="original_application_form_and_recent_picture"
                               @if($submission->original_application_form_and_recent_picture ?? false) checked @endif>
                        <label class="me-2" for="original_application_form_and_recent_picture">Original</label>
                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_application_form_and_recent_picture" name="photocopy_application_form_and_recent_picture"
                               @if($submission->photocopy_application_form_and_recent_picture ?? false) checked @endif>
                        <label class="me-2" for="photocopy_application_form_and_recent_picture">Photocopy</label>
                    </div>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">2. Security License</span>
                    <input class="form-check-input ms-5" type="checkbox" id="sbr" name="sbr" @if($submission->sbr ?? false) checked @endif>
                    <label class="me-2" for="sbr">SBR</label>
                    <input class="form-check-input ms-5" type="checkbox" id="original_security_license" name="original_security_license" @if($submission->original_security_license ?? false) checked @endif>
                    <label class="me-2" for="original_security_license">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_security_license" name="photocopy_security_license" @if($submission->photocopy_security_license ?? false) checked @endif>
                    <label class="me-2" for="photocopy_security_license">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_security_license ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">3. NBI Clearance</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_nbi_clearance" name="original_nbi_clearance" @if($submission->original_nbi_clearance ?? false) checked @endif>
                    <label class="me-2" for="original_nbi_clearance">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_nbi_clearance" name="photocopy_nbi_clearance" @if($submission->photocopy_nbi_clearance ?? false) checked @endif>
                    <label class="me-2" for="photocopy_nbi_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_nbi_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">4. National Police Clearance</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_national_police_clearance" name="original_national_police_clearance" @if($submission->original_national_police_clearance?? false) checked @endif>
                    <label class="me-2" for="original_national_police_clearance">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_national_police_clearance" name="photocopy_national_police_clearance" @if($submission->photocopy_national_police_clearance ?? false) checked @endif>
                    <label class="me-2" for="photocopy_national_police_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_national_police_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">5. Police Clearance</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_police_clearance" name="original_police_clearance" @if($submission->original_police_clearance ?? false) checked @endif>
                    <label class="me-2" for="original_police_clearance">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_police_clearance" name="photocopy_police_clearance" @if($submission->photocopy_police_clearance ?? false) checked @endif>
                    <label class="me-2" for="photocopy_police_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_police_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">6. Court Clearance</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_court_clearance" name="original_court_clearance" @if($submission->original_court_clearance?? false) checked @endif>
                    <label class="me-2" for="original_court_clearance">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_court_clearance" name="photocopy_court_clearance" @if($submission->photocopy_court_clearance ?? false) checked @endif>
                    <label class="me-2" for="photocopy_court_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_court_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">7. Barangay Clearance</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_barangay_clearance" name="original_barangay_clearance" @if($submission->original_barangay_clearance ?? false) checked @endif>
                    <label class="me-2" for="original_barangay_clearance">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_barangay_clearance" name="photocopy_barangay_clearance" @if($submission->photocopy_barangay_clearance ?? false) checked @endif>
                    <label class="me-2" for="photocopy_barangay_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_barangay_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">8. Neuro Psychiatric Test</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_neuro_psychiatric_test" name="original_neuro_psychiatric_test" @if($submission->original_neuro_psychiatric_test ?? false) checked @endif>
                    <label class="me-2" for="original_neuro_psychiatric_test">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_neuro_psychiatric_test" name="photocopy_neuro_psychiatric_test" @if($submission->photocopy_neuro_psychiatric_test ?? false) checked @endif>
                    <label class="me-2" for="photocopy_neuro_psychiatric_test">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_neuro_psychiatric_test ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">9. Drug Test</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_drug_test" name="original_drug_test" @if($submission->original_drug_test ?? false) checked @endif>
                    <label class="me-2" for="original_drug_test">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_drug_test" name="photocopy_drug_test" @if($submission->photocopy_drug_test ?? false) checked @endif>
                    <label class="me-2" for="photocopy_drug_test">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_drug_test ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">10. Diploma</span>
                    <input class="form-check-input ms-5" type="checkbox" id="diploma_hs" name="diploma_hs" @if($submission->diploma_hs ?? false) checked @endif>
                    <label class="me-2" for="diploma_hs">High School</label>
                    <input class="form-check-input ms-5" type="checkbox" id="diploma_college" name="diploma_college" @if($submission->diploma_college ?? false) checked @endif>
                    <label class="me-2" for="diploma_college">College</label>
                    <input class="form-check-input ms-5" type="checkbox" id="original_diploma" name="original_diploma" @if($submission->original_diploma ?? false) checked @endif>
                    <label class="me-2" for="original_diploma">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_diploma" name="photocopy_diploma" @if($submission->photocopy_diploma ?? false) checked @endif>
                    <label class="me-2" for="photocopy_diploma">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">11. Transcript of Records</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_transcript" name="original_transcript" @if($submission->original_transcript ?? false) checked @endif>
                    <label class="me-2" for="original_transcript">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_transcript" name="photocopy_transcript" @if($submission->photocopy_transcript ?? false) checked @endif>
                    <label class="me-2" for="photocopy_transcript">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">12. Training Certificate, Opening, Closing</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_training_certificate" name="original_training_certificate" @if($submission->original_training_certificate ?? false) checked @endif>
                    <label class="me-2" for="original_training_certificate">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_training_certificate" name="photocopy_training_certificate" @if($submission->photocopy_training_certificate ?? false) checked @endif>
                    <label class="me-2" for="photocopy_training_certificate">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_training_certificate ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">13. PSA Birth Certificate</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth" name="original_psa_birth" @if($submission->original_psa_birth ?? false) checked @endif>
                    <label class="me-2" for="original_psa_birth">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth" name="photocopy_psa_birth" @if($submission->photocopy_psa_birth ?? false) checked @endif>
                    <label class="me-2" for="photocopy_psa_birth">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">14. PSA Marriage Certificate</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_psa_marriage" name="original_psa_marriage" @if($submission->original_psa_marriage ?? false) checked @endif>
                    <label class="me-2" for="original_psa_marriage">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_marriage" name="photocopy_psa_marriage" @if($submission->photocopy_psa_marriage ?? false) checked @endif>
                    <label class="me-2" for="photocopy_psa_marriage">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">15. PSA Birth Certificates of Dependents (Children)</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth_dependents" name="original_psa_birth_dependents" @if($submission->original_psa_birth_dependents ?? false) checked @endif>
                    <label class="me-2" for="original_psa_birth_dependents">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth_dependents" name="photocopy_psa_birth_dependents" @if($submission->photocopy_psa_birth_dependents?? false) checked @endif>
                    <label class="me-2" for="photocopy_psa_birth_dependents">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold mb-3">16. Certificate of Employment (Previous Security Agency)</span>
                    <div class="mt-2">
                        <input class="form-check-input ms-5" type="checkbox" id="original_certificate_of_employment" name="original_certificate_of_employment" @if($submission->original_certificate_of_employment ?? false) checked @endif>
                        <label class="me-2" for="original_certificate_of_employment">Original</label>
                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_certificate_of_employment" name="photocopy_certificate_of_employment"
                               @if($submission->photocopy_certificate_of_employment ?? false) checked @endif>
                        <label class="me-2" for="photocopy_certificate_of_employment">Photocopy</label>
                    </div>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">17. Community Tax Certificate</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_community_tax_certificate" name="original_community_tax_certificate" @if($submission->original_community_tax_certificate ?? false) checked @endif>
                    <label class="me-2" for="original_community_tax_certificate">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_community_tax_certificate" name="photocopy_community_tax_certificate" @if($submission->photocopy_community_tax_certificate ?? false) checked @endif>
                    <label class="me-2" for="photocopy_community_tax_certificate">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">18. Tax Identification Number</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_tax_id_number" name="original_tax_id_number" @if($submission->original_tax_id_number ?? false) checked @endif>
                    <label class="me-2" for="original_tax_id_number">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_tax_id_number" name="photocopy_tax_id_number" @if($submission->photocopy_tax_id_number ?? false) checked @endif>
                    <label class="me-2" for="photocopy_tax_id_number">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">19. E1 Form SSS</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_e1_form" name="original_e1_form" @if($submission->original_e1_form ?? false) checked @endif>
                    <label class="me-2" for="original_e1_form">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_e1_form" name="photocopy_e1_form" @if($submission->photocopy_e1_form ?? false) checked @endif>
                    <label class="me-2" for="photocopy_e1_form">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">20. SSS</span>
                    <input class="form-check-input ms-5" type="checkbox" id="sss_id" name="sss_id" @if($submission->sss_id ?? false) checked @endif>
                    <label class="me-2" for="sss_id">SSS ID</label>
                    <input class="form-check-input ms-5" type="checkbox" id="sss_slip" name="sss_slip" @if($submission->sss_slip ?? false) checked @endif>
                    <label class="me-2" for="sss_slip">SSS Slip</label>
                    <input class="form-check-input ms-5" type="checkbox" id="original_sss_id" name="original_sss_id" @if($submission->original_sss_id ?? false) checked @endif>
                    <label class="me-2" for="original_sss_id">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_sss_id" name="photocopy_sss_id" @if($submission->photocopy_sss_id?? false) checked @endif>
                    <label class="me-2" for="photocopy_sss_id">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">21. PhilHealth ID</span>
                    <input class="form-check-input ms-5" type="checkbox" id="philhealth_mdr" name="philhealth_mdr" @if($submission->philhealth_mdr ?? false) checked @endif>
                    <label class="me-2" for="philhealth_mdr">MDR</label>
                    <input class="form-check-input ms-5" type="checkbox" id="original_philhealth_id" name="original_philhealth_id" @if($submission->original_philhealth_id ?? false) checked @endif>
                    <label class="me-2" for="original_philhealth_id">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_philhealth_id" name="photocopy_philhealth_id" @if($submission->photocopy_philhealth_id ?? false) checked @endif>
                    <label class="me-2" for="photocopy_philhealth_id">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_philhealth_id ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">22. Pag-IBIG ID</span>
                    <input class="form-check-input ms-5" type="checkbox" id="pagibig_mdf" name="pagibig_mdf" @if($submission->pagibig_mdf ?? false) checked @endif>
                    <label class="me-2" for="pagibig_mdf">MDF</label>
                    <input class="form-check-input ms-5" type="checkbox" id="original_pagibig_id" name="original_pagibig_id" @if($submission->original_pagibig_id ?? false) checked @endif>
                    <label class="me-2" for="original_pagibig_id">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_pagibig_id" name="photocopy_pagibig_id" @if($submission->photocopy_pagibig_id?? false) checked @endif>
                    <label class="me-2" for="photocopy_pagibig_id">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_pagibig_id ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">23. Statement of Account (Previous Loan)</span>
                    <input class="form-check-input ms-5" type="checkbox" id="original_statement_of_account" name="original_statement_of_account" @if($submission->original_statement_of_account ?? false) checked @endif>
                    <label class="me-2" for="original_statement_of_account">Original</label>
                    <input class="form-check-input ms-5" type="checkbox" id="photocopy_statement_of_account" name="photocopy_statement_of_account" @if($submission->photocopy_statement_of_account ?? false) checked @endif>
                    <label class="me-2" for="photocopy_statement_of_account">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            </tbody>
        </table>
        <div class="row mt-5 mb-5">
            <div class="col-md-6 text-center">
                <p class="fw-bold h4">@if($submission->complete_requirements)
                        Completed Requirements: Yes
                    @else
                        Completed Requirements: No
                    @endif</p>
            </div>
            <div class="col-md-6 text-center">
                <p class="fw-bold h4">@if($submission->qualified_for_loan)
                        Qualified For ESIAI Loan: Yes
                    @else
                        Qualified For ESIAI Loan: No
                    @endif</p>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-between">
        <div class="col-6 text-center mt-5 mb-5">
            <p>Submitted By:</p>
            <!-- Populate the submitted by section with the submission's user and submission date -->
            <h5 class="mb-0 fw-bolder">{{ $employee->first_name }} {{ $employee->last_name }} {{ $employee->suffix ?? '' }}</h5>
            <p>{{ ucwords($employee->getRoleNames()[0]) }} <br> {{ Carbon::parse($submission->created_at)->format('F d, Y') }}</p>
        </div>
        <div class="col-6 text-center mt-5 mb-5">
            <p>Released By:</p>
            <!-- Populate the submitted by section with the submission's user and submission date -->
            <h5 class="mb-0 fw-bolder border-bottom border-2 border-gray w-50 m-auto mb-1 mt-5"></h5>
            <p>HR Department <br> Date: <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</span>
        </div>
        <div class="col-6 text-center mt-5">
            <p>Received By:</p>
            <!-- Populate the submitted by section with the submission's user and submission date -->
            <h5 class="mb-0 fw-bolder">{{ $user->first_name ?? ''}} {{ $user->last_name ?? ''}} {{ $user->suffix ?? '' }}</h5>
            <p>{{ ucwords($user->getRoleNames()[0]) }} <br> {{ Carbon::parse($submission->created_at)->format('F d, Y') }}</p>
        </div>
        <div class="col-6 text-center mt-5">
            <p>Received By:</p>
            <!-- Populate the submitted by section with the submission's user and submission date -->
            <h5 class="mb-0 fw-bolder">{{ $employee->first_name }} {{ $employee->last_name }} {{ $employee->suffix ?? '' }}</h5>
            <p>{{ ucwords($employee->getRoleNames()[0]) }} <br> Date: <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</span>
            </p>
        </div>
        <div class="alert bg-warning-subtle text-center">This is document is system generated.</div>
    </div>
</div>
