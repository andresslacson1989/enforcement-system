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
    <div class="row">
      <form @can('edit first month performance evaluation form') action="/form/update/{{ str_replace(' ', '-', strtolower($submission->name)) }}/{{ $submission->id }}" method="put" id="first_month_performance_evaluation_form" @endcan>
        @csrf
        <div class="col-12">
          <div class="card">
            @include('content.snippets.form_header')
            <div class="container-fluid">
              <div class="row g-6">
                <div class="col-md-4 mb-2">
                  <label class="form-label fw-bold">Employee</label>
                  <div class="form-control-plaintext">Name</div>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="form-label fw-bold">Employee No</label>
                  <div class="form-control-plaintext">{{ $submission->employee_number }}</div>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="form-label fw-bold">Job Title</label>
                  <div class="form-control-plaintext">{{ $submission->job_title }}</div>
                </div>
              </div>

              <div class="row mt-4">
                <div class="col-12">
                  <h5 class="text-center fw-bold">Performance Criteria</h5>
                  <table class="table table-bordered table-sm">
                    <thead>
                    <tr>
                      <th colspan="2"></th>
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
                      <td class="text-center">@if($submission->knowledge_understanding_a == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->knowledge_understanding_a == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->knowledge_understanding_a == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->knowledge_understanding_a == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    <tr>
                      <td>b.</td>
                      <td>Familiarity with the layout and premises of the assigned area:</td>
                      <td class="text-center">@if($submission->knowledge_understanding_b == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->knowledge_understanding_b == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->knowledge_understanding_b == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->knowledge_understanding_b == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>

                    <tr>
                      <th colspan="6" class="fw-bold">2. Attendance and Punctuality</th>
                    </tr>
                    <tr>
                      <td>a.</td>
                      <td>Maintains good attendance and arrives punctually for shifts:</td>
                      <td class="text-center">@if($submission->attendance_a == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->attendance_a == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->attendance_a == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->attendance_a == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    <tr>
                      <td>b.</td>
                      <td>Adheres to assigned work schedules and break time:</td>
                      <td class="text-center">@if($submission->attendance_b == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->attendance_b == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->attendance_b == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->attendance_b == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>

                    <tr>
                      <th colspan="6" class="fw-bold">3. Observation and Reporting</th>
                    </tr>
                    <tr>
                      <td>a.</td>
                      <td>Demonstrate attentiveness and alertness during protocols:</td>
                      <td class="text-center">@if($submission->observation_a == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->observation_a == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->observation_a == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->observation_a == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    <tr>
                      <td>b.</td>
                      <td>Reports incident, suspicious activities, or safety hazards promptly:</td>
                      <td class="text-center">@if($submission->observation_b == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->observation_b == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->observation_b == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->observation_b == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>

                    <tr>
                      <th colspan="6" class="fw-bold">4. Communication Skills</th>
                    </tr>
                    <tr>
                      <td>a.</td>
                      <td>Communicates effectively with team members and supervisors:</td>
                      <td class="text-center">@if($submission->communication_a == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->communication_a == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->communication_a == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->communication_a == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    <tr>
                      <td>b.</td>
                      <td>Uses clear concise language in written reports:</td>
                      <td class="text-center">@if($submission->communication_b == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->communication_b == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->communication_b == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->communication_b == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>

                    <tr>
                      <th colspan="6" class="fw-bold">5. Professionalism and Customer Service</th>
                    </tr>
                    <tr>
                      <td>a.</td>
                      <td>Maintains a professional appearance and demeanor:</td>
                      <td class="text-center">@if($submission->professionalism_a == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->professionalism_a == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->professionalism_a == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->professionalism_a == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    <tr>
                      <td>b.</td>
                      <td>Provides courteous and helpful assistance to visitors and employees:</td>
                      <td class="text-center">@if($submission->professionalism_b == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->professionalism_b == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->professionalism_b == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->professionalism_b == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-6">
                  <h6 class="fw-bold">Areas of Strength:</h6>
                  <ul class="list-group">
                    @if($submission->strength_1)
                      <li class="list-group-item">{{ $submission->strength_1 }}</li>
                    @endif
                    @if($submission->strength_2)
                      <li class="list-group-item">{{ $submission->strength_2 }}</li>
                    @endif
                    @if($submission->strength_3)
                      <li class="list-group-item">{{ $submission->strength_3 }}</li>
                    @endif
                  </ul>
                </div>
                <div class="col-6">
                  <h6 class="fw-bold">Areas of Improvement:</h6>
                  <ul class="list-group">
                    @if($submission->improvement_1)
                      <li class="list-group-item">{{ $submission->improvement_1 }}</li>
                    @endif
                    @if($submission->improvement_2)
                      <li class="list-group-item">{{ $submission->improvement_2 }}</li>
                    @endif
                    @if($submission->improvement_3)
                      <li class="list-group-item">{{ $submission->improvement_3 }}</li>
                    @endif
                  </ul>
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
                      <td class="text-center">@if($submission->overall_standing == 'poor')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->overall_standing == 'fair')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->overall_standing == 'good')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                      <td class="text-center">@if($submission->overall_standing == 'excellent')
                          <i class="fas fa-check-circle"></i>
                        @else
                          <i class="far fa-circle"></i>
                        @endif</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <hr>
              <div class="row mt-3">
                <div class="col-6">
                  <h6 class="fw-bold text-center">Supervisor's Comment</h6>
                  <p class="comment-box">{{ $submission->supervisor_comment }}</p>
                </div>
                <div class="col-6">
                  <h6 class="fw-bold text-center">Security Personnel's Comment</h6>
                  <p class="comment-box">{{ $submission->security_comment }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  @else
    <div class="row">
      <form action="/form/{{ str_replace(' ', '-', strtolower($form_name)) }}/store" method="post" id="{{ str_replace(' ', '-', strtolower($form_name)) }}">
        @csrf
        <input type="hidden" name="meeting_date" id="meeting_date_input">
        <div class="col-12">
          <div class="card">
            @include('content.snippets.form_header')
            <div class="card-body pt-6">
              <div class="row">
                <div class="col-lg-9 mx-auto">
                  <!-- 1. Delivery Address -->
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
                    <div class="col-md-4 mb-2">
                      <label class="form-label" for="employee_name">Employee</label>
                      <select class="selectpicker w-100" data-style="btn-default" data-live-search="true" name="employee" id="employee">
                        <option value=" " disabled selected>Choose an option</option>
                        @forelse($guards as $item)
                          <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} {{ $item->suffix ?? '' }} [{{ ucwords($item->getRoleNames()[0]) }}]</option>
                        @empty
                          <option value="" disabled></option>
                        @endforelse
                      </select>
                    </div>
                    <div class="col-md-4 mb-2">
                      <label class="form-label" for="employee_number">Employee No</label>
                      <input class="form-control" type="text" id="employee_number" name="employee_number" aria-label="ID1234" aria-describedby="ID1234" value="" />
                    </div>
                    <div class="col-md-4 mb-2">
                      <label class="form-label" for="employee_number">Job Title</label>
                      <input class="form-control" type="text" id="job_title" name="job_title" aria-label="ID1234" aria-describedby="ID1234" value="" />
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label" for="deployment">Deployment</label>
                      <select id="deployment" name="deployment" class="selectpicker w-100" data-style="btn-default" data-live-search="true">
                        @forelse($deployment as $item)
                          <option value="{{ $item->id }}">{{ $item->name }} [{{ $item->address }}]</option>
                        @empty
                        @endforelse
                      </select>
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="form-label" for="supervisor">Supervisor</label>
                      <select class="selectpicker w-100" data-style="btn-default" data-live-search="true" name="supervisor" id="supervisor">
                        @forelse($dc as $item)
                          <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }} {{ $item->suffix ?? '' }}</option>
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
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-12 p-3">
                          <h5 class="text-center fw-bold">Performance Criteria</h5>
                          <table class="table table-bordered table-sm" id="performance-criteria-table">
                            <thead>
                            <tr>
                              <th colspan="2"></th>
                              <th class="text-center">Poor</th>
                              <th class="text-center">Fair</th>
                              <th class="text-center">Good</th>
                              <th class="text-center">Excellent</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- 1. Knowledge and Understanding -->
                            <tr>
                              <th colspan="6" class="fw-bold">1. Knowledge and Understanding</th>
                            </tr>
                            <tr>
                              <td class="col-1">a.</td>
                              <td>Demonstrate understanding of security protocols and procedures:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_poor" name="knowledge_understanding_a" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_fair" name="knowledge_understanding_a" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_good" name="knowledge_understanding_a" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_a_excellent" name="knowledge_understanding_a" value="excellent"></td>
                            </tr>
                            <tr>
                              <td class="col-1">b.</td>
                              <td>Familiarity with the layout and premises of the assigned area:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_poor" name="knowledge_understanding_b" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_fair" name="knowledge_understanding_b" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_good" name="knowledge_understanding_b" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="knowledge_understanding_b_excellent" name="knowledge_understanding_b" value="excellent"></td>
                            </tr>

                            <!-- 2. Attendance and Punctuality -->
                            <tr>
                              <th colspan="6" class="fw-bold">2. Attendance and Punctuality</th>
                            </tr>
                            <tr>
                              <td class="col-1">a.</td>
                              <td>Maintains good attendance and arrives punctually for shifts:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_poor" name="attendance_a" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_fair" name="attendance_a" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_good" name="attendance_a" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_a_excellent" name="attendance_a" value="excellent"></td>
                            </tr>
                            <tr>
                              <td class="col-1">b.</td>
                              <td>Adheres to assigned work schedules and break time:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_poor" name="attendance_b" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_fair" name="attendance_b" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_good" name="attendance_b" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="attendance_b_excellent" name="attendance_b" value="excellent"></td>
                            </tr>

                            <!-- 3. Observation and Reporting -->
                            <tr>
                              <th colspan="6" class="fw-bold">3. Observation and Reporting</th>
                            </tr>
                            <tr>
                              <td class="col-1">a.</td>
                              <td>Demonstrate attentiveness and alertness during protocols:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_poor" name="observation_a" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_fair" name="observation_a" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_good" name="observation_a" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_a_excellent" name="observation_a" value="excellent"></td>
                            </tr>
                            <tr>
                              <td class="col-1">b.</td>
                              <td>Reports incident, suspicious activities, or safety hazards promptly:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_poor" name="observation_b" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_fair" name="observation_b" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_good" name="observation_b" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="observation_b_excellent" name="observation_b" value="excellent"></td>
                            </tr>

                            <!-- 4. Communication Skills -->
                            <tr>
                              <th colspan="6" class="fw-bold">4. Communication Skills</th>
                            </tr>
                            <tr>
                              <td class="col-1">a.</td>
                              <td>Communicates effectively with team members and supervisors:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_poor" name="communication_a" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_fair" name="communication_a" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_good" name="communication_a" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_a_excellent" name="communication_a" value="excellent"></td>
                            </tr>
                            <tr>
                              <td class="col-1">b.</td>
                              <td>Uses clear concise language in written reports:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_poor" name="communication_b" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_fair" name="communication_b" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_good" name="communication_b" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="communication_b_excellent" name="communication_b" value="excellent"></td>
                            </tr>

                            <!-- 5. Professionalism and Customer Service -->
                            <tr>
                              <th colspan="6" class="fw-bold">5. Professionalism and Customer Service</th>
                            </tr>
                            <tr>
                              <td class="col-1">a.</td>
                              <td>Maintains a professional appearance and demeanor:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_poor" name="professionalism_a" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_fair" name="professionalism_a" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_good" name="professionalism_a" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_a_excellent" name="professionalism_a" value="excellent"></td>
                            </tr>
                            <tr>
                              <td class="col-1">b.</td>
                              <td>Provides courteous and helpful assistance to visitors and employees:</td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_poor" name="professionalism_b" value="poor"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_fair" name="professionalism_b" value="fair"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_good" name="professionalism_b" value="good"></td>
                              <td class="text-center"><input class="form-check-input" type="radio" id="professionalism_b_excellent" name="professionalism_b" value="excellent"></td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <!-- Areas of Strength and Improvement -->
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

                      <!-- Overall Standing (now with radio buttons) -->
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
                      <!-- Comments and Signatures -->
                      <div class="row mt-3">
                        <div class="col-6">
                          <h6 class="fw-bold text-center">Supervisor's Comment</h6>
                          <textarea class="form-control  mb-10" rows="5" id="supervisor_comment" name="supervisor_comment"></textarea>
                          <div class="col-12 text-center mt-4 fw-bold">
                            {{ $user->first_name }} {{ $user->last_name }} {{ $user->suffix ?? '' }}
                          </div>
                          <div class="text-center signature-text mt-1">Supervisor - {{ ucwords($user->getRoleNames()[0]) }}</div>
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
