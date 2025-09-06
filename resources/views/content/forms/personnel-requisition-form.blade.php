@php use App\Models\User; @endphp
@extends('layouts/layoutMaster')

@section('title', 'Personnel Requisition Form')

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
    'resources/assets/js/pages-personnel-requisition-form.js',
    'resources/assets/js/extended-ui-sweetalert2.js',
    ])
@endsection

@section('content')
    @if($submission)
        <div class="row">
            <div class="col-12">
                <form id="{{ str_replace(' ', '-', $form_name) }}" method="PUT" @can(config("permit.edit ".$form_name.".name"))  action="{{ route('forms.update', str_replace(' ', '-', $form_name) ) }}" @endcan>
                    <div class="card">
                        @include('content.snippets.form_header')
                        <div class="card-body pt-6">
                            @csrf
                            <h5>A. Operations Department Section</h5>
                            <hr class="mt-0">
                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <label for="detachment_id" class="form-label">Client Name:</label>
                                    <select class="form-control select2" id="detachment_id" name="detachment_id" required>
                                        <option value="" disabled selected>Select a client...</option>
                                        @foreach($detachments as $client)
                                            <option value="{{ $client->id }}" @if($client->id == $submission->detachment->id ?? '') selected @endif>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="pr_number" class="form-label">PR No.:</label>
                                    <input type="text" class="form-control" min="0" id="pr_number" name="pr_number" value="{{ str_pad(\App\Models\PersonnelRequisitionForm::latest()->value('id') + 1, 6, '0', STR_PAD_LEFT) }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Type of Security Personnel:</label>hp
                                    @foreach($personnel_types as $type)
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox" name="personnel_types[]"
                                                   value="{{ $type }}"
                                                   id="type_{{ Str::slug($type) }}"
                                                   @if(in_array($type, $submission->personnel_types ?? []))
                                                       checked
                                                @endif
                                            >
                                            <label class="form-check-label" for="type_{{ Str::slug($type) }}">{{ $type }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Purpose:</label>
                                    @php
                                        @endphp
                                    @foreach($purposes as $purpose)
                                        <div class="form-check">
                                            <label class="form-check-label" for="purpose_{{ Str::slug($purpose) }}">{{ $purpose }}</label>
                                            <input class="form-check-input"
                                                   type="checkbox" name="purpose[]"
                                                   value="{{ $purpose }}"
                                                   id="purpose_{{ Str::slug($purpose) }}"
                                                   @if(in_array($purpose, $submission->purpose ?? []))
                                                       checked
                                                @endif
                                            >
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="manpower_needed" class="form-label">Manpower Needed:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="manpower_needed" name="manpower_needed" value="{{ $submission->manpower_needed ?? ''}}" required>
                                            <span class="input-group-text">
                                            Personnel
                                        </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="years_of_experience" class="form-label">Years of Experience</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" value="{{ $submission->years_of_experience ?? ''}}">
                                            <span class="input-group-text">
                                            Years
                                        </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estimated_date_needed" class="form-label">Estimate Date Needed:</label>
                                        <input type="date" class="form-control" id="estimated_date_needed" name="estimated_date_needed" required
                                               value="{{ $submission->estimated_date_needed ? $submission->estimated_date_needed->format('Y-m-d') : ''}}">
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-4">Detachment Requirements</h5>
                            <hr class="mt-0">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="height_male" class="form-label">Height (Male)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="height_male" name="height_male" placeholder="e.g., 163" value="{{ $submission->height_male ?? ''}}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="height_female" class="form-label">Height (Female)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="height_female" name="height_female" placeholder="e.g., 163" value="{{ $submission->heigh_female ?? '' }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="weight_male" class="form-label">Weight (Male)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="weight_male" name="weight_male" placeholder="e.g., 75" value="{{ $submission->weight_male ?? '' }}">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="weight_female" class="form-label">Weight (Female)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="weight_female" name="weight_female" placeholder="e.g., 75" value="{{ $submission->weight_female ?? '' }}">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="education" class="form-label">Educational Attainment:</label>
                                <select class="form-control select2" id="education" name="education" required>
                                    @php
                                        $education = [
                                            'high_school_graduate',
                                            'college_level',
                                            'college_graduate'
                                        ]
                                    @endphp
                                    <option value="" disabled selected>Select an option...</option>
                                    @foreach($education as $item)
                                        <option value="{{ $item }}" @if($submission->education == $item) selected @endif>{{ ucwords(str_replace('_', ' ', $item)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="skills_experience" class="form-label">Skills/Experience Needed:</label>
                                <select id="skills_experience" class="select2 form-select" name="skills_experience[]" multiple>
                                    @foreach($all_tags as $tag)
                                        <option value="{{ $tag->name }}" @if(in_array($tag->name, $submission->skills_experience ?? [])) selected @endif >{{ ucwords($tag->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @can(config("permit.edit ".$form_name.".name"))
                                <div class="row mt-5">
                                    <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                        <button type="submit" class="btn btn-primary btn-lg">Update Form</button>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <form id="{{ str_replace(' ', '-', $form_name) }}" method="POST" @can(config("permit.fill ".$form_name.".name"))  action="{{ route('forms.store', str_replace(' ', '-', $form_name) ) }}" @endcan>
                    <div class="card">
                        @include('content.snippets.form_header')
                        <div class="card-body pt-6">
                            @csrf
                            <h5>A. Operations Department Section</h5>
                            <hr class="mt-0">
                            <div class="row mb-3">
                                <div class="col-md-10">
                                    <label for="detachment_id" class="form-label">Client Name:</label>
                                    <select class="form-control select2" id="detachment_id" name="detachment_id" required>
                                        <option value="" disabled selected>Select a client...</option>
                                        @foreach($detachments as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="pr_number" class="form-label">PR No.:</label>
                                    <input type="text" class="form-control" min="0" id="pr_number" name="pr_number"
                                           value="{{ str_pad(\App\Models\PersonnelRequisitionForm::latest()->value('id') + 1, 6, '0', STR_PAD_LEFT) }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Type of Security Personnel:</label>hp
                                    @foreach($personnel_types as $type)
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox" name="personnel_types[]"
                                                   value="{{ $type }}"
                                                   id="type_{{ Str::slug($type) }}"
                                            >
                                            <label class="form-check-label" for="type_{{ Str::slug($type) }}">{{ $type }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Purpose:</label>
                                    @php
                                        @endphp
                                    @foreach($purposes as $purpose)
                                        <div class="form-check">
                                            <label class="form-check-label" for="purpose_{{ Str::slug($purpose) }}">{{ $purpose }}</label>
                                            <input class="form-check-input"
                                                   type="checkbox" name="purpose[]"
                                                   value="{{ $purpose }}"
                                                   id="purpose_{{ Str::slug($purpose) }}"
                                            >
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="manpower_needed" class="form-label">Manpower Needed:</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="manpower_needed" name="manpower_needed" required>
                                            <span class="input-group-text">
                                            Personnel
                                        </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="years_of_experience" class="form-label">Years of Experience</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="years_of_experience" name="years_of_experience">
                                            <span class="input-group-text">
                                            Years
                                        </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estimated_date_needed" class="form-label">Estimate Date Needed:</label>
                                        <input type="date" class="form-control" id="estimated_date_needed" name="estimated_date_needed" required>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mt-4">Detachment Requirements</h5>
                            <hr class="mt-0">
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="height_male" class="form-label">Height (Male)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="height_male" name="height_male" placeholder="e.g., 163">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="height_female" class="form-label">Height (Female)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="height_female" name="height_female" placeholder="e.g., 163">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="weight_male" class="form-label">Weight (Male)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="weight_male" name="weight_male" placeholder="e.g., 75">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="weight_female" class="form-label">Weight (Female)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="weight_female" name="weight_female" placeholder="e.g., 75">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="education" class="form-label">Educational Attainment:</label>
                                <select class="form-control select2" id="education" name="education" required>
                                    <option value="" disabled selected>Select an option...</option>
                                    <option value="high_school_graduate">High School Graduate</option>
                                    <option value="college_level">College Level</option>
                                    <option value="college_graduate">College Graduate</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="skills_experience" class="form-label">Skills/Experience Needed:</label>
                                <select id="skills_experience" class="select2 form-select" name="skills_experience[]" multiple>
                                    @foreach($all_tags as $tag)
                                        <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row mt-5">
                                <div class="d-grid d-md-flex justify-content-md-end gap-2 mb-5">
                                    <button type="submit" class="btn btn-primary btn-lg">Submit Form</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
