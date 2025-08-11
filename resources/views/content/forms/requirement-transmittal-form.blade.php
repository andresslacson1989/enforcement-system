@php use App\Models\User; @endphp
@extends('layouts/layoutMaster')

@section('title', 'Activity Board')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss',
  'resources/assets/vendor/libs/animate-css/animate.scss"',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  ])
@endsection

<!-- Page Styles -->
{{--@section('page-style')--}}
{{--  @vite([--}}
{{--  //'resources/assets/vendor/scss/pages/wizard-ex-checkout.scss'--}}
{{--  ])--}}
{{--@endsection--}}

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite([
  'resources/assets/js/forms-requirement-transmittal-form.js',
  'resources/assets/js/extended-ui-sweetalert2.js',
  ])
@endsection

@section('content')

  @if($submission)
    <div class="row">
      <form @can('edit '.$form_name) action="/form/{{ str_replace(' ', '-', strtolower($submission->name)) }}/update/{{ $submission->id }}" method="put" id="requirement_transmittal_form" @endcan>
        @csrf
        <div class="col-12">
          <div class="card">
            @include('content.snippets.form_header')
            <div class="card-body pt-6">
              <div class="row">
                <div class="col-lg-9 mx-auto">
                  <!-- 1. Employee Info -->
                  <div class="row d-flex justify-content-around">
                    <div class="col">
                      <h5 class="mb-4">Employee Info</h5>
                    </div>
                    <div class="col">
                      <!-- Display the submission date -->
                      <h5 class="mb-4 text-end">{{ \Carbon\Carbon::parse($submission->created_at)->format('F d, Y') }}</h5>
                    </div>
                  </div>
                  <div class="row g-6">
                    <div class="col-md-6">
                      <label class="form-label" for="employee_name">Full Name</label>
                      <!-- Populate employee name from the submission's user -->
                      <input type="text" readonly class="form-control"
                             value="{{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="employee_number">Employee No</label>
                      <!-- Populate employee number from the submission's user -->
                      <input class="form-control" type="text" readonly aria-label="ID1234" aria-describedby="ID1234" value="{{ $user->employee_number ?? '' }}" />
                    </div>
                    <div class="col-md-12">
                      <label class="form-label" for="deployment">Deployment</label>
                      <!-- Populate deployment from the submission's detachment -->
                      <input type="text" id="deployment" name="deployment" class="form-control" placeholder="Manila" aria-label="Manila" value="{{ $user->detachment->name ?? '' }} [{{ $detachment->address ?? '' }}]" />
                    </div>
                  </div>
                  <hr />
                  <div class="row">
                    <div class="col">
                      <h5 class="mb-4">Requirements</h5>
                    </div>
                  </div>
                  <div class="row g-6">
                    <div class="col-md-12">
                      <table class="table table-bordered table-sm">
                        <thead>
                        <tr class="text-center">
                          <th>Requirements</th>
                          <th>Expiration</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- The following table rows are now populated with submitted data -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                1. Application Form with 2 pcs. 2x2 recent picture (white background)
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_application_form_and_recent_picture" name="original_application_form_and_recent_picture"
                                   @if($submission->original_application_form_and_recent_picture ?? false) checked @endif>
                            <label class="me-2" for="original_application_form_and_recent_picture">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_application_form_and_recent_picture" name="photocopy_application_form_and_recent_picture"
                                   @if($submission->photocopy_application_form_and_recent_picture ?? false) checked @endif>
                            <label class="me-2" for="photocopy_application_form_and_recent_picture">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                2. Security License
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="sbr" name="sbr" @if($submission->sbr ?? false) checked @endif>
                            <label class="me-2" for="sbr">SBR</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_security_license" name="original_security_license" @if($submission->original_security_license ?? false) checked @endif>
                            <label class="me-2" for="original_security_license">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_security_license" name="photocopy_security_license" @if($submission->photocopy_security_license ?? false) checked @endif>
                            <label class="me-2" for="photocopy_security_license">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_security_license" name="exp_security_license" value="{{ $submission->exp_security_license ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                3. NBI Clearance
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_nbi_clearance" name="original_nbi_clearance" @if($submission->original_nbi_clearance ?? false) checked @endif>
                            <label class="me-2" for="original_nbi_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_nbi_clearance" name="photocopy_nbi_clearance" @if($submission->photocopy_nbi_clearance ?? false) checked @endif>
                            <label class="me-2" for="photocopy_nbi_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_nbi_clearance" name="exp_nbi_clearance" value="{{ $submission->exp_nbi_clearance ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                4. National Police Clearance
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_national_police_clearance" name="original_national_police_clearance"
                                   @if($submission->original_national_police_clearance?? false) checked @endif>
                            <label class="me-2" for="original_national_police_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_national_police_clearance" name="photocopy_national_police_clearance"
                                   @if($submission->photocopy_national_police_clearance ?? false) checked @endif>
                            <label class="me-2" for="photocopy_national_police_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_national_police_clearance" name="exp_national_police_clearance"
                                   value="{{ $submission->exp_national_police_clearance ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">5. Police Clearance</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_police_clearance" name="original_police_clearance" @if($submission->original_police_clearance ?? false) checked @endif>
                            <label class="me-2" for="original_police_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_police_clearance" name="photocopy_police_clearance" @if($submission->photocopy_police_clearance ?? false) checked @endif>
                            <label class="me-2" for="photocopy_police_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_police_clearance" name="exp_police_clearance" value="{{ $submission->exp_police_clearance ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">6. Court Clearance</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_court_clearance" name="original_court_clearance" @if($submission->original_court_clearance?? false) checked @endif>
                            <label class="me-2" for="original_court_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_court_clearance" name="photocopy_court_clearance" @if($submission->photocopy_court_clearance ?? false) checked @endif>
                            <label class="me-2" for="photocopy_court_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_court_clearance" name="exp_court_clearance" value="{{ $submission->exp_court_clearance ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">7. Barangay Clearance</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_barangay_clearance" name="original_barangay_clearance" @if($submission->original_barangay_clearance ?? false) checked @endif>
                            <label class="me-2" for="original_barangay_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_barangay_clearance" name="photocopy_barangay_clearance" @if($submission->photocopy_barangay_clearance ?? false) checked @endif>
                            <label class="me-2" for="photocopy_barangay_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_barangay_clearance" name="exp_barangay_clearance" value="{{ $submission->exp_barangay_clearance ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">8. Neuro Psychiatric Test</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_neuro_psychiatric_test" name="original_neuro_psychiatric_test" @if($submission->original_neuro_psychiatric_test ?? false) checked @endif>
                            <label class="me-2" for="original_neuro_psychiatric_test">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_neuro_psychiatric_test" name="photocopy_neuro_psychiatric_test"
                                   @if($submission->photocopy_neuro_psychiatric_test ?? false) checked @endif>
                            <label class="me-2" for="photocopy_neuro_psychiatric_test">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_neuro_psychiatric_test" name="exp_neuro_psychiatric_test"
                                   value="{{ $submission->exp_neuro_psychiatric_test ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">9. Drug Test</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_drug_test" name="original_drug_test" @if($submission->original_drug_test ?? false) checked @endif>
                            <label class="me-2" for="original_drug_test">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_drug_test" name="photocopy_drug_test" @if($submission->photocopy_drug_test ?? false) checked @endif>
                            <label class="me-2" for="photocopy_drug_test">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_drug_test" name="exp_drug_test" value="{{ $submission->exp_drug_test ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">10. Diploma</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="diploma_hs" name="diploma_hs" @if($submission->diploma_hs ?? false) checked @endif>
                            <label class="me-2" for="diploma_hs">High School</label>
                            <input class="form-check-input ms-5" type="checkbox" id="diploma_college" name="diploma_college" @if($submission->diploma_college ?? false) checked @endif>
                            <label class="me-2" for="diploma_college">College</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_diploma" name="original_diploma" @if($submission->original_diploma ?? false) checked @endif>
                            <label class="me-2" for="original_diploma">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_diploma" name="photocopy_diploma" @if($submission->photocopy_diploma ?? false) checked @endif>
                            <label class="me-2" for="photocopy_diploma">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">11. Transcript of Records</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_transcript" name="original_transcript" @if($submission->original_transcript ?? false) checked @endif>
                            <label class="me-2" for="original_transcript">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_transcript" name="photocopy_transcript" @if($submission->photocopy_transcript ?? false) checked @endif>
                            <label class="me-2" for="photocopy_transcript">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">12. Training Certificate, Opening, Closing</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_training_certificate" name="original_training_certificate" @if($submission->original_training_certificate ?? false) checked @endif>
                            <label class="me-2" for="original_training_certificate">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_training_certificate" name="photocopy_training_certificate" @if($submission->photocopy_training_certificate ?? false) checked @endif>
                            <label class="me-2" for="photocopy_training_certificate">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_training_certificate" name="exp_training_certificate"
                                   value="{{ $submission->exp_training_certificate ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">13. PSA Birth Certificate</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth" name="original_psa_birth" @if($submission->original_psa_birth ?? false) checked @endif>
                            <label class="me-2" for="original_psa_birth">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth" name="photocopy_psa_birth" @if($submission->photocopy_psa_birth ?? false) checked @endif>
                            <label class="me-2" for="photocopy_psa_birth">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">14. PSA Marriage Certificate</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_psa_marriage" name="original_psa_marriage" @if($submission->original_psa_marriage ?? false) checked @endif>
                            <label class="me-2" for="original_psa_marriage">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_marriage" name="photocopy_psa_marriage" @if($submission->photocopy_psa_marriage ?? false) checked @endif>
                            <label class="me-2" for="photocopy_psa_marriage">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">15. PSA Birth Certificates of Dependents (Children)</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth_dependents" name="original_psa_birth_dependents" @if($submission->original_psa_birth_dependents ?? false) checked @endif>
                            <label class="me-2" for="original_psa_birth_dependents">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth_dependents" name="photocopy_psa_birth_dependents" @if($submission->photocopy_psa_birth_dependents?? false) checked @endif>
                            <label class="me-2" for="photocopy_psa_birth_dependents">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">16. Certificate of Employment (Previous Security Agency)</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_certificate_of_employment" name="original_certificate_of_employment"
                                   @if($submission->original_certificate_of_employment ?? false) checked @endif>
                            <label class="me-2" for="original_certificate_of_employment">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_certificate_of_employment" name="photocopy_certificate_of_employment"
                                   @if($submission->photocopy_certificate_of_employment ?? false) checked @endif>
                            <label class="me-2" for="photocopy_certificate_of_employment">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">17. Community Tax Certificate</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_community_tax_certificate" name="original_community_tax_certificate"
                                   @if($submission->original_community_tax_certificate ?? false) checked @endif>
                            <label class="me-2" for="original_community_tax_certificate">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_community_tax_certificate" name="photocopy_community_tax_certificate"
                                   @if($submission->photocopy_community_tax_certificate ?? false) checked @endif>
                            <label class="me-2" for="photocopy_community_tax_certificate">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">18. Tax Identification Number</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_tax_id_number" name="original_tax_id_number" @if($submission->original_tax_id_number ?? false) checked @endif>
                            <label class="me-2" for="original_tax_id_number">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_tax_id_number" name="photocopy_tax_id_number" @if($submission->photocopy_tax_id_number ?? false) checked @endif>
                            <label class="me-2" for="photocopy_tax_id_number">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">19. E1 Form SSS</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_e1_form" name="original_e1_form" @if($submission->original_e1_form ?? false) checked @endif>
                            <label class="me-2" for="original_e1_form">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_e1_form" name="photocopy_e1_form" @if($submission->photocopy_e1_form ?? false) checked @endif>
                            <label class="me-2" for="photocopy_e1_form">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">20. SSS</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="sss_id" name="sss_id" @if($submission->sss_id ?? false) checked @endif>
                            <label class="me-2" for="sss_id">SSS ID</label>
                            <input class="form-check-input ms-5" type="checkbox" id="sss_slip" name="sss_slip" @if($submission->sss_slip ?? false) checked @endif>
                            <label class="me-2" for="sss_slip">SSS Slip</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_sss_id" name="original_sss_id" @if($submission->original_sss_id ?? false) checked @endif>
                            <label class="me-2" for="original_sss_id">Original</label>

                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_sss_id" name="photocopy_sss_id" @if($submission->photocopy_sss_id?? false) checked @endif>
                            <label class="me-2" for="photocopy_sss_id">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">21. PhilHealth ID</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="philhealth_mdr" name="philhealth_mdr" @if($submission->philhealth_mdr ?? false) checked @endif>
                            <label class="me-2" for="philhealth_mdr">MDR</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_philhealth_id" name="original_philhealth_id" @if($submission->original_philhealth_id ?? false) checked @endif>
                            <label class="me-2" for="original_philhealth_id">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_philhealth_id" name="photocopy_philhealth_id" @if($submission->photocopy_philhealth_id ?? false) checked @endif>
                            <label class="me-2" for="photocopy_philhealth_id">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_philhealth_id" name="exp_philhealth_id" value="{{ $submission->exp_philhealth_id ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">22. Pag-IBIG ID</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="pagibig_mdf" name="pagibig_mdf" @if($submission->pagibig_mdf ?? false) checked @endif>
                            <label class="me-2" for="pagibig_mdf">MDF</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_pagibig_id" name="original_pagibig_id" @if($submission->original_pagibig_id ?? false) checked @endif>
                            <label class="me-2" for="original_pagibig_id">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_pagibig_id" name="photocopy_pagibig_id" @if($submission->photocopy_pagibig_id?? false) checked @endif>
                            <label class="me-2" for="photocopy_pagibig_id">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_pagibig_id" name="exp_pagibig_id" value="{{ $submission->exp_pagibig_id ?? '' }}" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">23. Statement of Account (Previous Loan)</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_statement_of_account" name="original_statement_of_account" @if($submission->original_statement_of_account ?? false) checked @endif>
                            <label class="me-2" for="original_statement_of_account">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_statement_of_account" name="photocopy_statement_of_account" @if($submission->photocopy_statement_of_account ?? false) checked @endif>
                            <label class="me-2" for="photocopy_statement_of_account">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <hr>
                </div>
                <div class="row">
                  <div class="row mt-5 text-center">
                    <div class="{{ $submission->status == 'approved' ? 'col-6' : 'col-12' }}">
                      <p>Submitted By:</p>
                      <!-- Populate the submitted by section with the submission's user and submission date -->
                      <h5 class="mb-0 fw-bolder">{{ $submitted_by->first_name }} {{ $submitted_by->last_name }} {{ $submitted_by->suffix ?? '' }}</h5>
                      <p>{{ ucwords($submitted_by->getRoleNames()[0]) }} - {{ \Carbon\Carbon::parse($submission->created_at)->format('F d, Y H:i:s') }}</p>
                    </div>
                    @if($submission->status == 'approved')
                      <div class="col-6">
                        <p>Approved By:</p>
                        <!-- Populate the submitted by section with the submission's user and submission date -->
                        <h5
                          class="mb-0 fw-bolder">{{ $approved_by->first_name ?? ''}} {{ $approved_by->last_name ?? ''}} {{ $approved_by->suffix ?? '' }}</h5>
                        <p>{{ ucwords($approved_by->getRoleNames()[0]) }} - {{ \Carbon\Carbon::parse($submission->date_approved)->format('F d, Y H:i:s') }}</p>
                      </div>
                    @endif
                  </div>
                  @if($submission->status == 'pending')
                    @can('edit requirement transmittal form')
                      <div class="d-grid d-md-flex justify-content-md-center gap-2 mb-5">
                        <button type="submit" class="btn btn-primary">Update Form</button>
                      </div>
                    @endcan
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    @if($submission->status == 'pending' || $submission->status == 'processing')
      @can('approve requirement transmittal form')
        <hr>
        <div class="row">
          <form action="/form/requirement-transmittal-form/approve" id="approval_form" method="patch">
            @csrf
            <input type="hidden" id="form_id" value="{{ $submission->id }}">
            <input type="hidden" id="form_type" value="{{ $submission->name }}">
            <div class="card">
              <div class="card-header sticky-element bg-label-warning d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                <h5 class="card-title mb-sm-0 me-2">For HR Department Use Only</h5>
              </div>
              <div class="card-body pt-6">
                <div class="row g-6">
                  <div class="col-md-12">
                    <div class="col">
                      <h5>REMARKS</h5>
                    </div>
                    <div class="row">
                      <div class="col-md mb-md-0 mb-2">
                        <div class="form-check custom-option custom-option-basic">
                          <label class="form-check-label custom-option-content" for="complete_requirements">
                            <input class="form-check-input" type="checkbox" id="complete_requirements" name="complete_requirements" @if($submission->complete_requirements ?? false) checked @endif />
                            <span class="custom-option-header">
                                    <span class="h6 mb-0">Completed Requirements</span>
                                  </span>
                            <span class="custom-option-body">
                                    <small class="option-text">Employee have satisfied the requirements.</small>
                                  </span>
                          </label>
                        </div>
                      </div>
                      <div class="col-md mb-md-0 mb-2">
                        <div class="form-check custom-option custom-option-basic">
                          <label class="form-check-label custom-option-content" for="qualified_for_loan">
                            <input class="form-check-input" type="checkbox" id="qualified_for_loan" name="qualified_for_loan" @if($submission->qualified_for_loan ?? false) checked @endif />
                            <span class="custom-option-header">
                                    <span class="h6 mb-0">Qualified for ESIAI Loan</span>
                                  </span>
                            <span class="custom-option-body">
                                    <small>Employee can request ESIAI Loan</small>
                                  </span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  @can('edit requirement transmittal form')
                    <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                      <button type="submit" class="btn btn-primary" id="approve_button">Approve</button>
                      <button type="submit" class="btn btn-danger" id="deny_button">Deny</button>
                    </div>
                  @endcan
                </div>
              </div>
            </div>
          </form>
        </div>
      @endcan
    @endif
  @else
    <div class="row">
      <form action="/form/requirement-transmittal-form/store" method="post" id="requirement_transmittal_form">
        @csrf
        <div class="col-12">
          <div class="card">
            @include('content.snippets.form_header')
            <div class="card-body pt-6">
              <div class="row">
                <div class="col-lg-9 mx-auto">
                  <!-- 1. Delivery Address -->
                  <div class="row d-flex justify-content-around">
                    <div class="col">
                      <h5 class="mb-4">Employee Info</h5>
                    </div>
                    <div class="col">
                      <h5 class="mb-4 text-end">{{ today()->format('F d, Y') }}</h5>
                    </div>
                  </div>
                  <div class="row g-6">
                    <div class="col-md-6">
                      <label class="form-label" for="employee_name">Full Name</label>
                      <input type="text" readonly class="form-control" value="{{ $user->first_name ?? ''  }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="employee_number">Employee No</label>
                      <input class="form-control" type="text" readonly aria-label="ID1234" aria-describedby="ID1234" value="{{ $user->employee_number ?? '' }}" />
                    </div>
                    <div class="col-md-12">
                      <label class="form-label" for="deployment">Deployment</label>
                      <input type="text" id="deployment" name="deployment" class="form-control" placeholder="Manila" aria-label="Manila" value="{{ $detachment->name  }} [{{ $detachment->address }}]" />
                    </div>
                  </div>
                  <hr />
                  <div class="row">
                    <div class="col">
                      <h5 class="mb-4">Requirements</h5>
                    </div>
                  </div>
                  <div class="row g-6">
                    <div class="col-md-12">
                      <table class="table table-bordered table-sm">
                        <thead>
                        <tr class="text-center">
                          <th>Requirements</th>
                          <th>Expiration</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                1. Application Form with 2 pcs. 2x2 recent picture (white background)
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_application_form_and_recent_picture" name="original_application_form_and_recent_picture">
                            <label class="me-2" for="original_application_form_and_recent_picture">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_application_form_and_recent_picture" name="photocopy_application_form_and_recent_picture">
                            <label class="me-2" for="photocopy_application_form_and_recent_picture">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                2. Security License
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="sbr" name="sbr">
                            <label class="me-2" for="sbr">SBR</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_security_license" name="original_security_license">
                            <label class="me-2" for="original_security_license">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_security_license" name="photocopy_security_license">
                            <label class="me-2" for="photocopy_security_license">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_security_license" name="exp_security_license" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                3. NBI Clearance
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_nbi_clearance" name="original_nbi_clearance">
                            <label class="me-2" for="original_nbi_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_nbi_clearance" name="photocopy_nbi_clearance">
                            <label class="me-2" for="photocopy_nbi_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_nbi_clearance" name="exp_nbi_clearance" />
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">
                                4. National Police Clearance
                              </div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_national_police_clearance" name="original_national_police_clearance">
                            <label class="me-2" for="original_national_police_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_national_police_clearance" name="photocopy_national_police_clearance">
                            <label class="me-2" for="photocopy_national_police_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_national_police_clearance" name="exp_national_police_clearance" />
                          </td>
                        </tr>
                        <!-- Row 5: Police Clearance -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">5. Police Clearance</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_police_clearance" name="original_police_clearance">
                            <label class="me-2" for="original_police_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_police_clearance" name="photocopy_police_clearance">
                            <label class="me-2" for="photocopy_police_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_police_clearance" name="exp_police_clearance" />
                          </td>
                        </tr>
                        <!-- Row 6: Court Clearance -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">6. Court Clearance</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_court_clearance" name="original_court_clearance">
                            <label class="me-2" for="original_court_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_court_clearance" name="photocopy_court_clearance">
                            <label class="me-2" for="photocopy_court_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_court_clearance" name="exp_court_clearance" />
                          </td>
                        </tr>
                        <!-- Row 7: Barangay Clearance -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">7. Barangay Clearance</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_barangay_clearance" name="original_barangay_clearance">
                            <label class="me-2" for="original_barangay_clearance">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_barangay_clearance" name="photocopy_barangay_clearance">
                            <label class="me-2" for="photocopy_barangay_clearance">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_barangay_clearance" name="exp_barangay_clearance" />
                          </td>
                        </tr>
                        <!-- Row 8: Neuro Psychiatric Test -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">8. Neuro Psychiatric Test</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_neuro_psychiatric_test" name="original_neuro_psychiatric_test">
                            <label class="me-2" for="original_neuro_psychiatric_test">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_neuro_psychiatric_test" name="photocopy_neuro_psychiatric_test">
                            <label class="me-2" for="photocopy_neuro_psychiatric_test">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_neuro_psychiatric_test" name="exp_neuro_psychiatric_test" />
                          </td>
                        </tr>
                        <!-- Row 9: Drug Test -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">9. Drug Test</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_drug_test" name="original_drug_test">
                            <label class="me-2" for="original_drug_test">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_drug_test" name="photocopy_drug_test">
                            <label class="me-2" for="photocopy_drug_test">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_drug_test" name="exp_drug_test" />
                          </td>
                        </tr>
                        <!-- Row 10: Diploma -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">10. Diploma</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="diploma_hs" name="diploma_hs">
                            <label class="me-2" for="diploma_hs">High School</label>
                            <input class="form-check-input ms-5" type="checkbox" id="diploma_college" name="diploma_college">
                            <label class="me-2" for="diploma_college">College</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_diploma" name="original_diploma">
                            <label class="me-2" for="original_diploma">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_diploma" name="photocopy_diploma">
                            <label class="me-2" for="photocopy_diploma">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 11: Transcript of Records -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">11. Transcript of Records</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_transcript" name="original_transcript">
                            <label class="me-2" for="original_transcript">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_transcript" name="photocopy_transcript">
                            <label class="me-2" for="photocopy_transcript">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 12: Training Certificate, Opening, Closing -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">12. Training Certificate, Opening, Closing</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_training_certificate" name="original_training_certificate">
                            <label class="me-2" for="original_training_certificate">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_training_certificate" name="photocopy_training_certificate">
                            <label class="me-2" for="photocopy_training_certificate">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_training_certificate" name="exp_training_certificate" />
                          </td>
                        </tr>
                        <!-- Row 13: PSA Birth Certificate -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">13. PSA Birth Certificate</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth" name="original_psa_birth">
                            <label class="me-2" for="original_psa_birth">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth" name="photocopy_psa_birth">
                            <label class="me-2" for="photocopy_psa_birth">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 14: PSA Marriage Certificate -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">14. PSA Marriage Certificate</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_psa_marriage" name="original_psa_marriage">
                            <label class="me-2" for="original_psa_marriage">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_marriage" name="photocopy_psa_marriage">
                            <label class="me-2" for="photocopy_psa_marriage">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 15: PSA Birth Certificates of Dependents (Children) -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">15. PSA Birth Certificates of Dependents (Children)</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth_dependents" name="original_psa_birth_dependents">
                            <label class="me-2" for="original_psa_birth_dependents">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth_dependents" name="photocopy_psa_birth_dependents">
                            <label class="me-2" for="photocopy_psa_birth_dependents">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 16: Certificate of Employment -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">16. Certificate of Employment (Previous Security Agency)</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_certificate_of_employment" name="original_certificate_of_employment">
                            <label class="me-2" for="original_certificate_of_employment">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_certificate_of_employment" name="photocopy_certificate_of_employment">
                            <label class="me-2" for="photocopy_certificate_of_employment">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 17: Community Tax Certificate -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">17. Community Tax Certificate</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_community_tax_certificate" name="original_community_tax_certificate">
                            <label class="me-2" for="original_community_tax_certificate">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_community_tax_certificate" name="photocopy_community_tax_certificate">
                            <label class="me-2" for="photocopy_community_tax_certificate">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 18: Tax Identification Number -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">18. Tax Identification Number</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_tax_id_number" name="original_tax_id_number">
                            <label class="me-2" for="original_tax_id_number">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_tax_id_number" name="photocopy_tax_id_number">
                            <label class="me-2" for="photocopy_tax_id_number">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 19: E1 Form SSS -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">19. E1 Form SSS</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_e1_form" name="original_e1_form">
                            <label class="me-2" for="original_e1_form">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_e1_form" name="photocopy_e1_form">
                            <label class="me-2" for="photocopy_e1_form">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 20: SSS ID -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">20. SSS</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="sss_id" name="sss_id">
                            <label class="me-2" for="sss_id">SSS ID</label>
                            <input class="form-check-input ms-5" type="checkbox" id="sss_slip" name="sss_slip">
                            <label class="me-2" for="sss_slip">SSS Slip</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_sss_id" name="original_sss_id">
                            <label class="me-2" for="original_sss_id">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_sss_id" name="photocopy_sss_id">
                            <label class="me-2" for="photocopy_sss_id">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        <!-- Row 21: PhilHealth ID -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">21. PhilHealth ID</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="philhealth_mdr" name="philhealth_mdr">
                            <label class="me-2" for="philhealth_mdr">MDR</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_philhealth_id" name="original_philhealth_id">
                            <label class="me-2" for="original_philhealth_id">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_philhealth_id" name="photocopy_philhealth_id">
                            <label class="me-2" for="photocopy_philhealth_id">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_philhealth_id" name="exp_philhealth_id" />
                          </td>
                        </tr>
                        <!-- Row 22: Pag-IBIG ID -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">22. Pag-IBIG ID</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="pagibig_mdf" name="pagibig_mdf">
                            <label class="me-2" for="pagibig_mdf">MDF</label>
                            <br>
                            <input class="form-check-input ms-5" type="checkbox" id="original_pagibig_id" name="original_pagibig_id">
                            <label class="me-2" for="original_pagibig_id">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_pagibig_id" name="photocopy_pagibig_id">
                            <label class="me-2" for="photocopy_pagibig_id">Photocopy</label>
                          </td>
                          <td>
                            <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_pagibig_id" name="exp_pagibig_id" />
                          </td>
                        </tr>
                        <!-- Row 23: Statement of Account -->
                        <tr>
                          <td>
                            <div class="row mb-2">
                              <div class="col-12 fw-bold">23. Statement of Account (Previous Loan)</div>
                            </div>
                            <input class="form-check-input ms-5" type="checkbox" id="original_statement_of_account" name="original_statement_of_account">
                            <label class="me-2" for="original_statement_of_account">Original</label>
                            <input class="form-check-input ms-5" type="checkbox" id="photocopy_statement_of_account" name="photocopy_statement_of_account">
                            <label class="me-2" for="photocopy_statement_of_account">Photocopy</label>
                          </td>
                          <td class="bg-dark-subtle"></td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <hr>
                  <div class="row g-6">
                    <div class="col-md-12">
                      <div class="alert alert-danger" role="alert">
                        <i class="icon-base ti tabler-alert-square-rounded icon-md me-1"></i>
                        This document is a system-generated form. By submitting this form, you acknowledge and agree that all relevant information, including your employee ID, will be recorded for processing and record-keeping purposes.
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col-md-12 text-center">
                        <p>Submitted By:</p>
                        <h5 class="mb-0 fw-bolder">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h5>
                        <p>{{ ucwords(auth()->user()->getRoleNames()[0]) }}</p>
                      </div>
                    </div>
                    <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                      <button type="submit" class="btn btn-primary">Submit Form</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  @endif
@endsection
