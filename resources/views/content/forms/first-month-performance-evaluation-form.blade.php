@php
    use App\Models\User;
    use Spatie\Permission\Models\Role;

    // This function will render a complete, checked radio button input
    function renderRatingRadio($name, $currentValue, $optionValue) {

      $checkedAttribute = ($currentValue === $optionValue) ? 'checked' : '';
      echo "<input class='form-check-input' type='radio' name='{$name}' id='{$name}_{$optionValue}' value='{$optionValue}' {$checkedAttribute}>";
    }

@endphp

@extends('layouts/layoutMaster')

@section('title', 'Activity Board')

@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    ])
@endsection

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

@section('page-script')
    @vite([
    'resources/assets/js/forms-first-month-performance-evaluation-form.js',
    'resources/assets/js/forms-selects.js',
    'resources/assets/js/extended-ui-sweetalert2.js',
    ])
@endsection

@section('content')
    <style>
        /* Add this CSS rule to fix the z-index issue */
        .flatpickr-calendar {
            z-index: 99999 !important; /* A value higher than SweetAlert's modal */
        }

        .overall_standing {
            pointer-events: none;
        }
    </style>
    @if($submission)
        {{-- This is now an EDIT form --}}
        <div class="row">
            <form id="{{ strtolower(str_replace(' ', '-', $form_name) )}}" method="PUT"
                  @can(config("permit.fill ".strtolower($form_name).".name"))  action="{{ route('forms.update', [strtolower(str_replace(' ', '-', $form_name)), $submission->id]) }}" @endcan>
                @csrf
                @method('PUT')
                {{--  <input type="hidden" id="employee" name="employee" value="{{ $submission->employee_id }}">
                  <input type="hidden" id="employee_number" name="employee_number" value="{{ $submission->employee_number }}">
                  <input type="hidden" id="deployment" name="deployment" value="{{ $submission->deployment }}">
                  <input type="hidden" id="job_title" name="job_title" value="{{ $submission->job_title }}">
                  <input type="hidden" id="supervisor" name="supervisor" value="{{ $submission->supervisor }}">--}}
                <div class="col-12">
                    <div class="card">
                        @include('content.snippets.form_header')
                        <div class="container-fluid p-4">
                            <div class="row g-6">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label fw-bold">Employee</label>
                                    <div class="form-control-plaintext">{{ $employee->name ?? ' Employee not found' }}</div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label fw-bold">Employee No</label>
                                    <div class="form-control-plaintext">{{ $submission->employee_number ?? '' }}</div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label fw-bold">Detachment</label>
                                    <div class="form-control-plaintext">{{ $submission->detachment->name ?? '' }}</div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label fw-bold">Job Title</label>
                                    <div class="form-control-plaintext">{{ ucwords( Role::findById($employee->primary_role_id)->name ?? '')  }}</div>
                                </div>
                            </div>
                            <div class="row mt-12">
                                <div class="col-12">
                                    <h5 class="text-center fw-bold">Performance Criteria</h5>
                                    <table class="table table-bordered table-sm performance-criteria-table" id="performance-criteria-table-edit">
                                        <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Criteria</th>
                                            <th class="text-center">Poor</th>
                                            <th class="text-center">Fair</th>
                                            <th class="text-center">Good</th>
                                            <th class="text-center">Excellent</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th colspan="6" class="fw-bold">1. Knowledge and Understanding</th>
                                        </tr>
                                        <tr>
                                            <td>a.</td>
                                            <td>Demonstrate understanding of security protocols and procedures:</td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_a', $submission->knowledge_understanding_a, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_a', $submission->knowledge_understanding_a, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_a', $submission->knowledge_understanding_a, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_a', $submission->knowledge_understanding_a, 'excellent'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>b.</td>
                                            <td>Familiarity with the layout and premises of the assigned area:</td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_b', $submission->knowledge_understanding_b, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_b', $submission->knowledge_understanding_b, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_b', $submission->knowledge_understanding_b, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('knowledge_understanding_b', $submission->knowledge_understanding_b, 'excellent'); ?></td>
                                        </tr>

                                        {{-- Repeat for all other criteria --}}
                                        <tr>
                                            <th colspan="6" class="fw-bold">2. Attendance and Punctuality</th>
                                        </tr>
                                        <tr>
                                            <td>a.</td>
                                            <td>Maintains good attendance and arrives punctually for shifts:</td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_a', $submission->attendance_a, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_a', $submission->attendance_a, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_a', $submission->attendance_a, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_a', $submission->attendance_a, 'excellent'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>b.</td>
                                            <td>Adheres to assigned work schedules and break time:</td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_b', $submission->attendance_b, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_b', $submission->attendance_b, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_b', $submission->attendance_b, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('attendance_b', $submission->attendance_b, 'excellent'); ?></td>
                                        </tr>

                                        <tr>
                                            <th colspan="6" class="fw-bold">3. Observation and Reporting</th>
                                        </tr>
                                        <tr>
                                            <td>a.</td>
                                            <td>Demonstrate attentiveness and alertness during protocols:</td>
                                            <td class="text-center"><?php renderRatingRadio('observation_a', $submission->observation_a, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('observation_a', $submission->observation_a, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('observation_a', $submission->observation_a, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('observation_a', $submission->observation_a, 'excellent'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>b.</td>
                                            <td>Reports incident, suspicious activities, or safety hazards promptly:</td>
                                            <td class="text-center"><?php renderRatingRadio('observation_b', $submission->observation_b, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('observation_b', $submission->observation_b, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('observation_b', $submission->observation_b, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('observation_b', $submission->observation_b, 'excellent'); ?></td>
                                        </tr>

                                        <tr>
                                            <th colspan="6" class="fw-bold">4. Communication Skills</th>
                                        </tr>
                                        <tr>
                                            <td>a.</td>
                                            <td>Communicates effectively with team members and supervisors:</td>
                                            <td class="text-center"><?php renderRatingRadio('communication_a', $submission->communication_a, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('communication_a', $submission->communication_a, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('communication_a', $submission->communication_a, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('communication_a', $submission->communication_a, 'excellent'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>b.</td>
                                            <td>Uses clear concise language in written reports:</td>
                                            <td class="text-center"><?php renderRatingRadio('communication_b', $submission->communication_b, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('communication_b', $submission->communication_b, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('communication_b', $submission->communication_b, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('communication_b', $submission->communication_b, 'excellent'); ?></td>
                                        </tr>

                                        <tr>
                                            <th colspan="6" class="fw-bold">5. Professionalism and Customer Service</th>
                                        </tr>
                                        <tr>
                                            <td>a.</td>
                                            <td>Maintains a professional appearance and demeanor:</td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_a', $submission->professionalism_a, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_a', $submission->professionalism_a, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_a', $submission->professionalism_a, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_a', $submission->professionalism_a, 'excellent'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>b.</td>
                                            <td>Provides courteous and helpful assistance to visitors and employees:</td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_b', $submission->professionalism_b, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_b', $submission->professionalism_b, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_b', $submission->professionalism_b, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('professionalism_b', $submission->professionalism_b, 'excellent'); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <h6 class="fw-bold">Areas of Strength:</h6>
                                    <input type="text" class="form-control mb-2" name="strength_1" value="{{ $submission->strength_1 }}" placeholder="1.">
                                    <input type="text" class="form-control mb-2" name="strength_2" value="{{ $submission->strength_2 }}" placeholder="2.">
                                    <input type="text" class="form-control" name="strength_3" value="{{ $submission->strength_3 }}" placeholder="3.">
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-bold">Areas of Improvement:</h6>
                                    <input type="text" class="form-control mb-2" name="improvement_1" value="{{ $submission->improvement_1 }}" placeholder="1.">
                                    <input type="text" class="form-control mb-2" name="improvement_2" value="{{ $submission->improvement_2 }}" placeholder="2.">
                                    <input type="text" class="form-control" name="improvement_3" value="{{ $submission->improvement_3 }}" placeholder="3.">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                        <tr>
                                            <th class="fw-bold text-center" colspan="4">Overall Standing</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Poor</th>
                                            <th class="text-center">Fair</th>
                                            <th class="text-center">Good</th>
                                            <th class="text-center">Excellent</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center"><?php renderRatingRadio('overall_standing', $submission->overall_standing, 'poor'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('overall_standing', $submission->overall_standing, 'fair'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('overall_standing', $submission->overall_standing, 'good'); ?></td>
                                            <td class="text-center"><?php renderRatingRadio('overall_standing', $submission->overall_standing, 'excellent'); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <h6 class="fw-bold text-center">Supervisor's Comment</h6>
                                    <textarea class="form-control" name="supervisor_comment" rows="5">{{ $submission->supervisor_comment }}</textarea>
                                    <div class="col-12 text-center mt-4 fw-bold">
                                        {{ $submitted_by->first_name }} {{ $submitted_by->last_name }} {{ $submitted_by->suffix ?? '' }}
                                    </div>
                                    <div class="text-center signature-text mt-1">Supervisor - {{ ucwords($submitted_by->getRoleNames()->first()) }}</div>
                                </div>
                                <div class="col-6">
                                    <h6 class="fw-bold text-center">Security Personnel's Comment</h6>
                                    <textarea class="form-control" name="security_comment" rows="5">{{ $submission->security_comment }}</textarea>
                                    <div class="col-12 text-center">
                                        <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; </span>
                                    </div>
                                    <div class="text-center signature-text mt-1">Printed Name/Sgd</div>
                                </div>
                            </div>
                        </div>
                        @if($submission->status == 'submitted')
                            @can(config("permit.edit ".$form_name.".name"))
                                <div class="row mt-4 p-4 mb-4">
                                    <div class="d-grid d-md-flex justify-content-md-end gap-2">
                                        <button type="submit" class="btn btn-primary">Update Form</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </form>
        </div>
    @else
        {{-- This is the CREATE form --}}
        <div class="row">
            <form id="{{ strtolower(str_replace(' ', '-', $form_name) )}}" method="POST"
                  @can(config("permit.fill ".strtolower($form_name).".name"))  action="{{ route('forms.store', strtolower(str_replace(' ', '-', $form_name)) ) }}" @endcan>
                @csrf
                <input type="hidden" name="meeting_date" id="meeting_date_input">
                <div class="col-12">
                    <div class="card">
                        @include('content.snippets.form_header')
                        <div class="card-body pt-6">
                            <div class="row">
                                <div class="col-lg-9 mx-auto">
                                    <div class="row d-flex justify-content-around">
                                        <div class="col-md-12">
                                            <h4>Security Personnel Performance Evaluation</h4>
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-4">Employee Info</h5>
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-4 text-end">{{ today()->format('F d, Y') }}</h5>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label" for="employee_id">Employee</label>
                                            <select class="select2 w-100" name="employee_id" id="employee_id">
                                                <option value=" " disabled selected>Choose an option</option>
                                                @forelse($guards as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }} [{{ ucwords($item->getRoleNames()->first()) }}]</option>
                                                @empty
                                                    <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label" for="employee_number">Employee No</label>
                                            <input class="form-control" type="text" id="employee_number" name="employee_number" aria-label="ID1234" aria-describedby="ID1234" value="" />
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label" for="detachment">Detachment</label>
                                            <input class="form-control" type="text" id="detachment_name" name="detachment_name" value="" readonly />
                                            <input class="form-control" type="hidden" id="detachment_id" name="detachment_id" value="" />

                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label" for="submitted_by">Supervisor</label>
                                            <input class="form-control" type="text" aria-label="submitted_by" aria-describedby="submitted_by"
                                                   value="{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }} {{ $user->suffix ?? '' }}" readonly />
                                            <input type="hidden" id="submitted_by" name="submitted_by" aria-label="submitted_by" aria-describedby="submitted_by"
                                                   value="{{ $user->id ?? '' }}" />
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="mb-4">Requirements</h5>
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-12 p-3">
                                                    <h5 class="text-center fw-bold">Performance Criteria</h5>
                                                    <table class="table table-bordered table-sm performance-criteria-table" id="performance-criteria-table">
                                                        <thead>
                                                        <tr>
                                                            <th colspan="2" class="text-center">Criteria</th>
                                                            <th class="text-center">Poor</th>
                                                            <th class="text-center">Fair</th>
                                                            <th class="text-center">Good</th>
                                                            <th class="text-center">Excellent</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <th colspan="6" class="fw-bold">1. Knowledge and Understanding</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">a.</td>
                                                            <td>Demonstrate understanding of security protocols and procedures:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_poor_new" name="knowledge_understanding_a" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_fair_new" name="knowledge_understanding_a" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_good_new" name="knowledge_understanding_a" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_excellent_new" name="knowledge_understanding_a" value="excellent"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">b.</td>
                                                            <td>Familiarity with the layout and premises of the assigned area:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_poor_new" name="knowledge_understanding_b" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_fair_new" name="knowledge_understanding_b" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_good_new" name="knowledge_understanding_b" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_excellent_new" name="knowledge_understanding_b" value="excellent"></td>
                                                        </tr>

                                                        <tr>
                                                            <th colspan="6" class="fw-bold">2. Attendance and Punctuality</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">a.</td>
                                                            <td>Maintains good attendance and arrives punctually for shifts:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_poor_new" name="attendance_a" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_fair_new" name="attendance_a" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_good_new" name="attendance_a" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_excellent_new" name="attendance_a" value="excellent"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">b.</td>
                                                            <td>Adheres to assigned work schedules and break time:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_poor_new" name="attendance_b" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_fair_new" name="attendance_b" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_good_new" name="attendance_b" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_excellent_new" name="attendance_b" value="excellent"></td>
                                                        </tr>

                                                        <tr>
                                                            <th colspan="6" class="fw-bold">3. Observation and Reporting</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">a.</td>
                                                            <td>Demonstrate attentiveness and alertness during protocols:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_poor_new" name="observation_a" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_fair_new" name="observation_a" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_good_new" name="observation_a" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_excellent_new" name="observation_a" value="excellent"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">b.</td>
                                                            <td>Reports incident, suspicious activities, or safety hazards promptly:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_poor_new" name="observation_b" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_fair_new" name="observation_b" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_good_new" name="observation_b" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_excellent_new" name="observation_b" value="excellent"></td>
                                                        </tr>

                                                        <tr>
                                                            <th colspan="6" class="fw-bold">4. Communication Skills</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">a.</td>
                                                            <td>Communicates effectively with team members and supervisors:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_poor_new" name="communication_a" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_fair_new" name="communication_a" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_good_new" name="communication_a" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_excellent_new" name="communication_a" value="excellent"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">b.</td>
                                                            <td>Uses clear concise language in written reports:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_poor_new" name="communication_b" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_fair_new" name="communication_b" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_good_new" name="communication_b" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_excellent_new" name="communication_b" value="excellent"></td>
                                                        </tr>

                                                        <tr>
                                                            <th colspan="6" class="fw-bold">5. Professionalism and Customer Service</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">a.</td>
                                                            <td>Maintains a professional appearance and demeanor:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_poor_new" name="professionalism_a" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_fair_new" name="professionalism_a" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_good_new" name="professionalism_a" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_excellent_new" name="professionalism_a" value="excellent"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-1">b.</td>
                                                            <td>Provides courteous and helpful assistance to visitors and employees:</td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_poor_new" name="professionalism_b" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_fair_new" name="professionalism_b" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_good_new" name="professionalism_b" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_excellent_new" name="professionalism_b" value="excellent"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <div class="card p-2">
                                                        <h6 class="fw-bold">Areas of Strength:</h6>
                                                        <div class="row">
                                                            <div class="col-12 mb-1">
                                                                <input type="text" class="form-control form-control-sm" id="strength_1" name="strength_1" placeholder="1">
                                                            </div>
                                                            <div class="col-12 mb-1">
                                                                <input type="text" class="form-control form-control-sm" id="strength_2" name="strength_2" placeholder="2">
                                                            </div>
                                                            <div class="col-12 mb-1">
                                                                <input type="text" class="form-control form-control-sm" id="strength_3" name="strength_3" placeholder="3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="card p-2">
                                                        <h6 class="fw-bold">Areas of Improvement:</h6>
                                                        <div class="row">
                                                            <div class="col-12 mb-1">
                                                                <input type="text" class="form-control form-control-sm" id="improvement_1" name="improvement_1" placeholder="1">
                                                            </div>
                                                            <div class="col-12 mb-1">
                                                                <input type="text" class="form-control form-control-sm" id="improvement_2" name="improvement_2" placeholder="2">
                                                            </div>
                                                            <div class="col-12 mb-1">
                                                                <input type="text" class="form-control form-control-sm" id="improvement_3" name="improvement_3" placeholder="3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                        <tr>
                                                            <th class="fw-bold text-center" colspan="4">Overall Standing</th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center">Poor</th>
                                                            <th class="text-center">Fair</th>
                                                            <th class="text-center">Good</th>
                                                            <th class="text-center">Excellent</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td class="text-center"><input class="form-check-input overall_standing" type="radio" id="overall_standing_poor" name="overall_standing" value="poor"></td>
                                                            <td class="text-center"><input class="form-check-input overall_standing" type="radio" id="overall_standing_fair" name="overall_standing" value="fair"></td>
                                                            <td class="text-center"><input class="form-check-input overall_standing" type="radio" id="overall_standing_good" name="overall_standing" value="good"></td>
                                                            <td class="text-center"><input class="form-check-input overall_standing" type="radio" id="overall_standing_excellent" name="overall_standing" value="excellent"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mt-3">
                                                <div class="col-6">
                                                    <h6 class="fw-bold text-center">Supervisor's Comment</h6>
                                                    <textarea class="form-control  mb-10" rows="5" id="supervisor_comment" name="supervisor_comment"></textarea>
                                                    <div class="col-12 text-center mt-4 fw-bold">
                                                        {{ $user->first_name }} {{ $user->last_name }} {{ $user->suffix ?? '' }}
                                                    </div>
                                                    <div class="text-center signature-text mt-1">Supervisor - {{ ucwords($user->getRoleNames()->first()) }}</div>
                                                </div>
                                                <div class="col-6 mb-10">
                                                    <h6 class="fw-bold text-center">Security Personnel's Comment</h6>
                                                    <textarea class="form-control  mb-10" rows="5" id="security_comment" name="security_comment"></textarea>
                                                    <div class="col-12 text-center">
                                                        <span class="w-px-200 border-bottom border-gray border-2 text-center">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; </span>
                                                    </div>
                                                    <div class="text-center signature-text mt-1">Printed Name/Sgd</div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
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
