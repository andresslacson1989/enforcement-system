{{-- ## EDIT USER MODAL ## --}}
<div class="modal fade" id="edit_profile_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl"> {{-- Increased modal size to modal-xl --}}
        <form id="edit_profile_form" action="/staffs/store" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title">Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="user_id" name="user_id">
                    <h5>Personal Information</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                            <span class="text-danger error-text first_name_error"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                            <span class="text-danger error-text last_name_error"></span>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Suffix</label>
                            <input type="text" class="form-control" id="suffix" name="suffix">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Employee Number</label>
                            <input type="text" class="form-control" id="employee_number" name="employee_number">
                            <span class="text-danger error-text employee_number_error"></span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="select2 form-select w-100" d
                                    id="gender" name="gender">
                                <option value="" disabled selected>Select a gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select class="select2 form-select w-100"
                                    id="status"
                                    name="status">
                                <option value="" disabled selected>Select a Status</option>
                                <option value="hired">Hired</option>
                                <option value="re_hired">Re-hired</option>
                                <option value="floating">Floating</option>
                                <option value="on_leave">On Leave</option>
                                <option value="resigned">Resigned</option>
                                <option value="preventive_suspension">Preventive Suspension</option>
                            </select>
                        </div>
                        @can(config('permit.change personnel role'))
                            <div class="col-md-3 mb-3">
                                <label for="role" class="form-label">Role (Position)</label>
                                <select class="select2 form-select w-100"
                                        name="role"
                                        id="role">
                                    <option value="" disabled selected>Select a Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text role_error"></span>
                            </div>
                        @endcan
                    </div>

                    {{-- Address Information --}}
                    <hr>
                    <h5 class="mt-3">Address</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Street</label>
                            <input type="text" class="form-control" id="street" name="street">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Province</label>
                            <input type="text" class="form-control" id="province" name="province">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code">
                        </div>
                    </div>

                    {{-- Contact & Account Information --}}
                    <hr>
                    <h5 class="mt-3">Contact & Account</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <span class="text-danger error-text email_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password (Optional)</label>
                            <input type="text" class="form-control" name="password" placeholder="Leave blank to use default password" autocomplete="off">
                            <span class="text-danger error-text password_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="text" class="form-control" name="password_confirmation" autocomplete="off">
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="d-grid d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary" id="modal_button">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
