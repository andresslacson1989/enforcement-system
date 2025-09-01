<div class="modal fade" id="users_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-user">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal_title">Add New User</h3>
                    <p class="text-muted">Fill in the form below to add or update a user.</p>
                </div>
                <form id="users_form" class="row g-3" onsubmit="return false">
                    @csrf
                    {{-- This hidden input tells the backend the context of the submission --}}
                    <input type="hidden" name="context" value="{{ $context ?? 'personnel' }}">
                    <ul class="nav nav-tabs nav-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#personal-info" role="tab" aria-selected="true">Personal Info</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#address-info" role="tab" aria-selected="false" tabindex="-1">Address</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#gov-ids" role="tab" aria-selected="false" tabindex="-1">Government IDs</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#system-info" role="tab" aria-selected="false" tabindex="-1">System Info</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane fade show active" id="personal-info" role="tabpanel">
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="John" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Doe" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="middle_name">Middle Name</label>
                                    <input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="Cruz" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="suffix">Suffix</label>
                                    <input type="text" id="suffix" name="suffix" class="form-control" placeholder="Jr." />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select id="gender" name="gender" class="form-select select2-modal">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="birth_date">Birth Date</label>
                                    <input type="text" id="birthdate" name="birthdate" class="form-control flatpickr-date" placeholder="YYYY-MM-DD" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="phone_number">Phone Number</label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="+63 912 345 6789" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="john.doe@example.com" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" name="submit_action" value="personal" class="btn btn-secondary btn-sm">Save Personal Info</button>
                                </div>
                            </div>
                        </div>

                        <!-- Address Tab -->
                        <div class="tab-pane fade" id="address-info" role="tabpanel">
                            <div class="row g-3 mt-3">
                                <div class="col-12">
                                    <label class="form-label" for="street">Street</label>
                                    <input type="text" id="street" name="street" class="form-control" placeholder="123 Main St" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="city">City</label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="Manila" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="province">Province</label>
                                    <input type="text" id="province" name="province" class="form-control" placeholder="Metro Manila" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="zip_code">Zip Code</label>
                                    <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="1000" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" name="submit_action" value="address" class="btn btn-secondary btn-sm">Save Address</button>
                                </div>
                            </div>
                        </div>

                        <!-- Government IDs Tab -->
                        <div class="tab-pane fade" id="gov-ids" role="tabpanel">
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="sss_number">SSS Number</label>
                                    <input type="text" id="sss_number" name="sss_number" class="form-control" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="philhealth_number">PhilHealth Number</label>
                                    <input type="text" id="philhealth_number" name="philhealth_number" class="form-control" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="pagibig_number">Pag-IBIG Number</label>
                                    <input type="text" id="pagibig_number" name="pagibig_number" class="form-control" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" name="submit_action" value="gov-ids" class="btn btn-secondary btn-sm">Save Government IDs</button>
                                </div>
                            </div>
                        </div>

                        <!-- System Info Tab -->
                        <div class="tab-pane fade" id="system-info" role="tabpanel">
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="employee_number">Employee Number</label>
                                    <input type="text" id="employee_number" name="employee_number" class="form-control" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="telegram_chat_id">Telegram Chat ID</label>
                                    <input type="text" id="telegram_chat_id" name="telegram_chat_id" class="form-control" placeholder="123456789" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="primary_role_id">Role</label>
                                    <select id="primary_role_id" name="primary_role_id" class="form-select select2-modal">
                                        <option value="" disabled selected>Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucwords($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                {{-- Conditionally show the Detachment field based on the context --}}
                                @if(isset($context) && $context === 'detachment-view')
                                    {{-- On detachment page, the ID is passed as a hidden field --}}
                                    <input type="hidden" name="detachment_id" value="{{ $detachment_id ?? '' }}">
                                @elseif(!isset($context) || $context !== 'staffs')
                                    {{-- On personnel page, show the dropdown. On staffs page, hide it completely. --}}
                                    <div class="col-md-6">
                                        <label class="form-label" for="detachment_id">Detachment</label>
                                        <select id="detachment_id" name="detachment_id" class="form-select select2-modal">
                                            <option value="" disabled selected>Select Detachment</option>
                                            @if(isset($detachments))
                                                @foreach($detachments as $detachment)
                                                    <option value="{{ $detachment->id }}">{{ $detachment->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="invalid-feedback error-text"></div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <label class="form-label" for="status">Status</label>
                                    <select id="status" name="status" class="form-select select2-modal">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="hired">Hired</option>
                                        <option value="floating">Floating</option>
                                        <option value="on_leave">On Leave</option>
                                        <option value="resigned">Resigned</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <div class="invalid-feedback error-text"></div>
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" name="submit_action" value="system" class="btn btn-secondary btn-sm">Save System Info</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" name="submit_action" value="all" class="btn btn-primary me-sm-3 me-1" id="modal_button">Save All Changes</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
