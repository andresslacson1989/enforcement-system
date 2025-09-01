@php
    use App\Models\User;
    use Spatie\Permission\Models\Role;

    // This function will render a complete, checked radio button input
    function renderRatingRadio($name, $currentValue, $optionValue) {

      $checkedAttribute = ($currentValue === $optionValue) ? 'checked' : '';
      echo "<input class='form-check-input' type='radio' name='{$name}' id='{$name}_{$optionValue}' value='{$optionValue}' {$checkedAttribute}>";
    }

@endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            @include('content.snippets.enforcement_header')
            <div class="container-fluid p-4">
                <div class="row g-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>
                                <label class="form-label fw-bold">Employee</label>
                                <div class="form-control-plaintext">{{ $employee->name ?? ' Employee not found' }}</div>
                            </td>
                            <td>
                                <label class="form-label fw-bold">Employee No</label>
                                <div class="form-control-plaintext">{{ $submission->employee_number ?? '' }}</div>
                            </td>
                            <td>
                                <label class="form-label fw-bold">Detachment</label>
                                <div class="form-control-plaintext">{{ $submission->detachment->name ?? '' }}</div>
                            </td>
                            <td>
                                <label class="form-label fw-bold">Job Title</label>
                                <div class="form-control-plaintext">{{ ucwords( Role::findById($employee->primary_role_id)->name ?? '')  }}</div>
                            </td>
                        </tr>
                    </table>
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
            @if($submission->status == 'pending')
                @can(config("permit.approve ".$form_name.".name"))
                    <div class="row mt-4 p-4 mb-4">
                        <div class="d-grid d-md-flex justify-content-md-end gap-2">
                            <button type="submit" class="btn btn-primary">Update Form</button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
