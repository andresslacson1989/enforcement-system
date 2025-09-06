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
                        <i class="ms-5 fa-regular {{ $submission->original_application_form_and_recent_picture ? 'fa-square-check' : 'fa-square' }}"></i>
                        <label class="me-2" for="original_application_form_and_recent_picture">Original</label>
                        <i class="ms-5 fa-regular {{ $submission->photocopy_application_form_and_recent_picture ? 'fa-square-check' : 'fa-square' }}"></i>
                        <label class="me-2" for="photocopy_application_form_and_recent_picture">Photocopy</label>
                    </div>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">2. Security License</span>
                    <i class="ms-5 fa-regular {{ $submission->sbr ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="sbr">SBR</label>
                    <i class="ms-5 fa-regular {{ $submission->original_security_license ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_security_license">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_security_license ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_security_license">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_security_license ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">3. NBI Clearance</span>
                    <i class="ms-5 fa-regular {{ $submission->original_nbi_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_nbi_clearance">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_nbi_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_nbi_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_nbi_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">4. National Police Clearance</span>
                    <i class="ms-5 fa-regular {{ $submission->original_national_police_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_national_police_clearance">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_national_police_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_national_police_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_national_police_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">5. Police Clearance</span>
                    <i class="ms-5 fa-regular {{ $submission->original_police_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_police_clearance">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_police_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_police_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_police_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">6. Court Clearance</span>
                    <i class="ms-5 fa-regular {{ $submission->original_court_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_court_clearance">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_court_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_court_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_court_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">7. Barangay Clearance</span>
                    <i class="ms-5 fa-regular {{ $submission->original_barangay_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_barangay_clearance">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_barangay_clearance ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_barangay_clearance">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_barangay_clearance ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">8. Neuro Psychiatric Test</span>
                    <i class="ms-5 fa-regular {{ $submission->original_neuro_psychiatric_test ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_neuro_psychiatric_test">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_neuro_psychiatric_test ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_neuro_psychiatric_test">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_neuro_psychiatric_test ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">9. Drug Test</span>
                    <i class="ms-5 fa-regular {{ $submission->original_drug_test ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_drug_test">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_drug_test ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_drug_test">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_drug_test ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">10. Diploma</span>
                    <i class="ms-5 fa-regular {{ $submission->diploma_hs ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="diploma_hs">High School</label>
                    <i class="ms-5 fa-regular {{ $submission->diploma_college ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="diploma_college">College</label>
                    <i class="ms-5 fa-regular {{ $submission->original_diploma ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_diploma">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_diploma ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_diploma">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">11. Transcript of Records</span>
                    <i class="ms-5 fa-regular {{ $submission->original_transcript ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_transcript">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_transcript ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_transcript">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">12. Training Certificate, Opening, Closing</span>
                    <i class="ms-5 fa-regular {{ $submission->original_training_certificate ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_training_certificate">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_training_certificate ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_training_certificate">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_training_certificate ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">13. PSA Birth Certificate</span>
                    <i class="ms-5 fa-regular {{ $submission->original_psa_birth ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_psa_birth">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_psa_birth ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_psa_birth">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">14. PSA Marriage Certificate</span>
                    <i class="ms-5 fa-regular {{ $submission->original_psa_marriage ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_psa_marriage">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_psa_marriage ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_psa_marriage">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">15. PSA Birth Certificates of Dependents (Children)</span>
                    <i class="ms-5 fa-regular {{ $submission->original_psa_birth_dependents ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_psa_birth_dependents">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_psa_birth_dependents ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_psa_birth_dependents">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold mb-3">16. Certificate of Employment (Previous Security Agency)</span>
                    <div class="mt-2">
                        <i class="ms-5 fa-regular {{ $submission->original_certificate_of_employment ? 'fa-square-check' : 'fa-square' }}"></i>
                        <label class="me-2" for="original_certificate_of_employment">Original</label>
                        <i class="ms-5 fa-regular {{ $submission->photocopy_certificate_of_employment ? 'fa-square-check' : 'fa-square' }}"></i>
                        <label class="me-2" for="photocopy_certificate_of_employment">Photocopy</label>
                    </div>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">17. Community Tax Certificate</span>
                    <i class="ms-5 fa-regular {{ $submission->original_community_tax_certificate ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_community_tax_certificate">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_community_tax_certificate ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_community_tax_certificate">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">18. Tax Identification Number</span>
                    <i class="ms-5 fa-regular {{ $submission->original_tax_id_number ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_tax_id_number">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_tax_id_number ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_tax_id_number">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">19. E1 Form SSS</span>
                    <i class="ms-5 fa-regular {{ $submission->original_e1_form ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_e1_form">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_e1_form ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_e1_form">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">20. SSS</span>
                    <i class="ms-5 fa-regular {{ $submission->sss_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="sss_id">SSS ID</label>
                    <i class="ms-5 fa-regular {{ $submission->sss_slip ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="sss_slip">SSS Slip</label>
                    <i class="ms-5 fa-regular {{ $submission->original_sss_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_sss_id">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_sss_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_sss_id">Photocopy</label>
                </td>
                <td class="bg-dark-subtle"></td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">21. PhilHealth ID</span>
                    <i class="ms-5 fa-regular {{ $submission->philhealth_mdr ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="philhealth_mdr">MDR</label>
                    <i class="ms-5 fa-regular {{ $submission->original_philhealth_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_philhealth_id">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_philhealth_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_philhealth_id">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_philhealth_id ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">22. Pag-IBIG ID</span>
                    <i class="ms-5 fa-regular {{ $submission->pagibig_mdf ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="pagibig_mdf">MDF</label>
                    <i class="ms-5 fa-regular {{ $submission->original_pagibig_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_pagibig_id">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_pagibig_id ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="photocopy_pagibig_id">Photocopy</label>
                </td>
                <td>
                    {{ $submission->exp_pagibig_id ?? '--' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="fw-bold">23. Statement of Account (Previous Loan)</span>
                    <i class="ms-5 fa-regular {{ $submission->original_statement_of_account ? 'fa-square-check' : 'fa-square' }}"></i>
                    <label class="me-2" for="original_statement_of_account">Original</label>
                    <i class="ms-5 fa-regular {{ $submission->photocopy_statement_of_account ? 'fa-square-check' : 'fa-square' }}"></i>
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
