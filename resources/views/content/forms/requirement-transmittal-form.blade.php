@php use App\Models\User; @endphp
@extends('layouts/layoutMaster')

@section('title', 'Recruitment Transmittal Form')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
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
    'resources/assets/vendor/libs/select2/select2.js',
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
            <form @can(config("permit.edit ".$form_name.".name")) action="/forms/update/{{ str_replace(' ', '-', strtolower($submission->name)) }}/{{ $submission->id }}" method="put" id="requirement_transmittal_form" @endcan>
                @csrf
                <input type="hidden" value="{{ $submission->id }}" id="form_id" name="form_id">
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
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Full Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $employee->first_name }}" placeholder="First Name" id="first_name" name="first_name" />
                                                <input type="text" class="form-control" value="{{ $employee->middle_name }}" placeholder="Middle Name" id="middle_name" name="middle_name" />
                                                <input type="text" class="form-control" value="{{ $employee->last_name }}" placeholder="Last Name" id="last_name" name="last_name" />
                                                <input type="text" class="form-control" value="{{ $employee->suffix ?? '' }}" placeholder="Suffix" id="suffix" name="suffix" />
                                                <select class="form-select" name="gender" id="gender">
                                                    <option value="male" @if($employee->gender == 'male') selected @endif>Male</option>
                                                    <option value="female" @if($employee->gender == 'female') selected @endif>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="{{ $employee->street }}" placeholder="Street" id="street" name="street" />
                                                <input type="text" class="form-control" value="{{ $employee->city }}" placeholder="City" id="city" name="city" />
                                                <input type="text" class="form-control" value="{{ $employee->province }}" placeholder="Province" id="province" name="province" />
                                                <input type="text" class="form-control" value="{{ $employee->zip_code }}" placeholder="Zip Code" id="zip_code" name="zip_code" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" type="email" aria-label="email" aria-describedby="email@example.com" value="{{ $employee->email }}" id="email" name="email" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone_number">Phone No</label>
                                            <input class="form-control" type="number" aria-label="phone-number" aria-describedby="09xx xxxx xxx" value="{{ $employee->phone_number }}" id="phone_number" name="phone_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="employee_number">Employee No</label>
                                            <input class="form-control" type="text" aria-label="ID1234" aria-describedby="ID1234" value="{{ $employee->employee_number }}" id="employee_number" name="employee_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="detachment_id">Deployment</label>
                                            <select id="detachment_id"
                                                    name="detachment_id"
                                                    class="form-select select2 w-100">
                                                <option value="" selected disabled>Choose an item</option>
                                                @forelse($detachments as $item)

                                                    <option value="{{ $item->id }}" @if($employee->detachment_id == $item->id) selected @endif>{{ $item->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
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
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_security_license" name="original_security_license"
                                                               @if($submission->original_security_license ?? false) checked @endif>
                                                        <label class="me-2" for="original_security_license">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_security_license" name="photocopy_security_license"
                                                               @if($submission->photocopy_security_license ?? false) checked @endif>
                                                        <label class="me-2" for="photocopy_security_license">Photocopy</label>
                                                    </td>
                                                    <td>
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_security_license" name="exp_security_license"
                                                               value="{{ $submission->exp_security_license ?? '' }}" />
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
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_nbi_clearance" name="exp_nbi_clearance"
                                                               value="{{ $submission->exp_nbi_clearance ?? '' }}" />
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
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_police_clearance" name="original_police_clearance"
                                                               @if($submission->original_police_clearance ?? false) checked @endif>
                                                        <label class="me-2" for="original_police_clearance">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_police_clearance" name="photocopy_police_clearance"
                                                               @if($submission->photocopy_police_clearance ?? false) checked @endif>
                                                        <label class="me-2" for="photocopy_police_clearance">Photocopy</label>
                                                    </td>
                                                    <td>
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_police_clearance" name="exp_police_clearance"
                                                               value="{{ $submission->exp_police_clearance ?? '' }}" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row mb-2">
                                                            <div class="col-12 fw-bold">6. Court Clearance</div>
                                                        </div>
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_court_clearance" name="original_court_clearance" @if($submission->original_court_clearance?? false) checked @endif>
                                                        <label class="me-2" for="original_court_clearance">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_court_clearance" name="photocopy_court_clearance"
                                                               @if($submission->photocopy_court_clearance ?? false) checked @endif>
                                                        <label class="me-2" for="photocopy_court_clearance">Photocopy</label>
                                                    </td>
                                                    <td>
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_court_clearance" name="exp_court_clearance"
                                                               value="{{ $submission->exp_court_clearance ?? '' }}" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row mb-2">
                                                            <div class="col-12 fw-bold">7. Barangay Clearance</div>
                                                        </div>
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_barangay_clearance" name="original_barangay_clearance"
                                                               @if($submission->original_barangay_clearance ?? false) checked @endif>
                                                        <label class="me-2" for="original_barangay_clearance">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_barangay_clearance" name="photocopy_barangay_clearance"
                                                               @if($submission->photocopy_barangay_clearance ?? false) checked @endif>
                                                        <label class="me-2" for="photocopy_barangay_clearance">Photocopy</label>
                                                    </td>
                                                    <td>
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_barangay_clearance" name="exp_barangay_clearance"
                                                               value="{{ $submission->exp_barangay_clearance ?? '' }}" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row mb-2">
                                                            <div class="col-12 fw-bold">8. Neuro Psychiatric Test</div>
                                                        </div>
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_neuro_psychiatric_test" name="original_neuro_psychiatric_test"
                                                               @if($submission->original_neuro_psychiatric_test ?? false) checked @endif>
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
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_training_certificate" name="original_training_certificate"
                                                               @if($submission->original_training_certificate ?? false) checked @endif>
                                                        <label class="me-2" for="original_training_certificate">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_training_certificate" name="photocopy_training_certificate"
                                                               @if($submission->photocopy_training_certificate ?? false) checked @endif>
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
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_psa_birth_dependents" name="original_psa_birth_dependents"
                                                               @if($submission->original_psa_birth_dependents ?? false) checked @endif>
                                                        <label class="me-2" for="original_psa_birth_dependents">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_psa_birth_dependents" name="photocopy_psa_birth_dependents"
                                                               @if($submission->photocopy_psa_birth_dependents?? false) checked @endif>
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
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_philhealth_id" name="exp_philhealth_id"
                                                               value="{{ $submission->exp_philhealth_id ?? '' }}" />
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
                                                        <input type="date" placeholder="Month day, Year" class="form-control  exp_date text-center " id="exp_pagibig_id" name="exp_pagibig_id"
                                                               value="{{ $submission->exp_pagibig_id ?? '' }}" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="row mb-2">
                                                            <div class="col-12 fw-bold">23. Statement of Account (Previous Loan)</div>
                                                        </div>
                                                        <input class="form-check-input ms-5" type="checkbox" id="original_statement_of_account" name="original_statement_of_account"
                                                               @if($submission->original_statement_of_account ?? false) checked @endif>
                                                        <label class="me-2" for="original_statement_of_account">Original</label>
                                                        <input class="form-check-input ms-5" type="checkbox" id="photocopy_statement_of_account" name="photocopy_statement_of_account"
                                                               @if($submission->photocopy_statement_of_account ?? false) checked @endif>
                                                        <label class="me-2" for="photocopy_statement_of_account">Photocopy</label>
                                                    </td>
                                                    <td class="bg-dark-subtle"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 text-center mt-5 mb-5">
                                        <p>Submitted By:</p>
                                        <!-- Populate the submitted by section with the submission's user and submission date -->
                                        <h5 class="mb-0 fw-bolder">{{ $employee->first_name }} {{ $employee->last_name }} {{ $employee->suffix ?? '' }}</h5>
                                        <p>{{ ucwords($employee->getRoleNames()[0]) }} <br> {{ \Carbon\Carbon::parse($submission->created_at)->format('F d, Y') }}</p>
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
                                        <p>{{ ucwords($user->getRoleNames()[0] ?? '') }} <br> {{ \Carbon\Carbon::parse($submission->created_at)->format('F d, Y') }}</p>
                                    </div>
                                    <div class="col-6 text-center mt-5">
                                        <p>Received By:</p>
                                        <!-- Populate the submitted by section with the submission's user and submission date -->
                                        <h5 class="mb-0 fw-bolder">{{ $employee->first_name }} {{ $employee->last_name }} {{ $employee->suffix ?? '' }}</h5>
                                        <p>{{ ucwords($employee->getRoleNames()[0]) }} <br> Date: <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</span>
                                        </p>
                                    </div>
                                    @can(config("permit.edit requirement transmittal form.name"))
                                        <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                            <button type="submit" class="btn btn-primary">Update Form</button>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @can(config("permit.edit requirement transmittal form.name"))
            <div class="row">
                <form action="/form/requirement-transmittal-form/approve" id="approval_form" method="patch">
                    @csrf
                    <input type="hidden" id="form_id" value="{{ $submission->id }}">
                    <input type="hidden" id="form_type" value="{{ $submission->name }}">
                    <div class="card">
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
                                @can(config("permit.edit requirement transmittal form.name"))
                                    <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                        <button type="submit" class="btn btn-primary" id="approve_button">Update Remarks</button>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endcan
    @else
        <div class="row">
            <form action="/forms/store/requirement-transmittal-form" method="post" id="requirement_transmittal_form">
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
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Full Name & Gender</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="" placeholder="First Name" id="first_name" name="first_name" />
                                                <input type="text" class="form-control" value="" placeholder="Middle Name" id="middle_name" name="middle_name" />
                                                <input type="text" class="form-control" value="" placeholder="Last Name" id="last_name" name="last_name" />
                                                <input type="text" class="form-control" value="" placeholder="Suffix" id="suffix" name="suffix" />
                                                <select class="form-select" name="gender" id="gender">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="employee_name">Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="" placeholder="Street" id="street" name="street" />
                                                <input type="text" class="form-control" value="" placeholder="City" id="city" name="city" />
                                                <input type="text" class="form-control" value="" placeholder="Province" id="province" name="province" />
                                                <input type="text" class="form-control" value="" placeholder="Zip Code" id="zip_code" name="zip_code" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="email">Email</label>
                                            <input class="form-control" type="email" aria-label="email" aria-describedby="email@example.com" value="" id="email" name="email" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="phone_number">Phone No</label>
                                            <input class="form-control" type="number" aria-label="phone-number" aria-describedby="09xx xxxx xxx" value="" id="phone_number" name="phone_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="employee_number">Employee No</label>
                                            <input class="form-control" type="text" aria-label="ID1234" aria-describedby="ID1234" value="" id="employee_number" name="employee_number" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="detachment_id">Deployment</label>
                                            <select id="detachment_id"
                                                    name="detachment_id"
                                                    class="form-select select2 w-100">
                                                <option value="" selected disabled>Choose an item</option>
                                                @forelse($detachments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
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
                                                This document is a system-generated form. By submitting this form, you acknowledge and agree that all relevant information, including your employee ID, will be recorded for processing and
                                                record-keeping purposes.
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
