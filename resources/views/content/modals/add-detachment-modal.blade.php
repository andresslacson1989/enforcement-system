
<!-- Extra Large Modal -->
<div class="modal fade" id="add_detachment_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel4">Add New Detachment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container mt-4">
          <form action="/detachments/store" method="POST" id="add_detachment_form">
            @csrf {{-- CSRF Protection --}}

            <div class="row">
              {{-- First Column --}}
              <div class="col-md-6">

                <!-- Basic Information Card -->
                <div class="card mb-4">
                  <div class="card-header">
                    Basic Information
                  </div>
                  <div class="card-body">
                    <!-- Detachment Name -->
                    <div class="mb-3">
                      <label for="name" class="form-label">Detachment Name</label>
                      <input type="text" class="form-control" id="name" name="name" value="{{ old('name', '') }}" required>
                    </div>

                    <!-- Assigned Officer -->
                    <div class="mb-3">
                      <label for="assigned_officer" class="form-label">Assigned Officer ID (Commander)</label>
                      <select
                        class="selectpicker"
                        id="assigned_officer"
                        data-width="100%"
                        name="assigned_officer"
                        data-live-search="true"
                        data-style="btn-default"
                      >
                        @foreach ($officers as $officer)
                          <option value="{{ $officer->id }}"  >
                            {{ $officer->name }}
                          </option>
                        @endforeach
                      </select>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Location Card -->
                <div class="card mb-4">
                  <div class="card-header">
                    Location Details
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" class="form-control" id="street" name="street" value="{{ old('street', '') }}" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', '') }}" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="province" class="form-label">Province</label>
                        <input type="text" class="form-control" id="province" name="province" value="{{ old('province', '') }}" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', '') }}" required>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Duty Configuration Card -->
                <div class="card mb-4">
                  <div class="card-header">
                    Duty & Shift Configuration
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label for="hours_per_shift" class="form-label">Hours/Shift</label>
                        <input type="number" class="form-control" id="hours_per_shift" name="hours_per_shift" value="{{ old('hours_per_shift', '') }}">
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="max_hrs_duty" class="form-label">Max Hours Duty</label>
                        <input type="number" class="form-control" id="max_hrs_duty" name="max_hrs_duty" value="{{ old('max_hrs_duty', '') }}">
                      </div>
                      <div class="col-md-4 mb-3">
                        <label for="max_ot" class="form-label">Max OT Hours</label>
                        <input type="number" class="form-control" id="max_ot" name="max_ot" value="{{ old('max_ot', '') }}">
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              {{-- Second Column --}}
              <div class="col-md-6">
                <!-- Pay Rates Card -->
                <div class="card mb-4">
                  <div class="card-header">
                    Pay Rate Configuration Per Hour
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4 mb-3"><label for="hr_rate" class="form-label">Hourly Rate</label><input type="number" step="0.01" class="form-control" name="hr_rate" value="{{ old('hr_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="ot_rate" class="form-label">OT Rate</label><input type="number" step="0.01" class="form-control" name="ot_rate" value="{{ old('ot_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="nd_rate" class="form-label">Night Diff Rate</label><input type="number" step="0.01" class="form-control" name="nd_rate" value="{{ old('nd_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="rdd_rate" class="form-label">Rest Day Rate</label><input type="number" step="0.01" class="form-control" name="rdd_rate" value="{{ old('rdd_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="rdd_ot_rate" class="form-label">Rest Day OT</label><input type="number" step="0.01" class="form-control" name="rdd_ot_rate" value="{{ old('rdd_ot_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="hol_rate" class="form-label">Holiday Rate</label><input type="number" step="0.01" class="form-control" name="hol_rate" value="{{ old('hol_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="hol_ot_rate" class="form-label">Holiday OT</label><input type="number" step="0.01" class="form-control" name="hol_ot_rate" value="{{ old('hol_ot_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="sh_rate" class="form-label">Special Hol.</label><input type="number" step="0.01" class="form-control" name="sh_rate" value="{{ old('sh_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="sh_ot_rate" class="form-label">Special Hol. OT</label><input type="number" step="0.01" class="form-control" name="sh_ot_rate" value="{{ old('sh_ot_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="rd_hol_rate" class="form-label">RD + Hol.</label><input type="number" step="0.01" class="form-control" name="rd_hol_rate" value="{{ old('rd_hol_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="rd_hol_ot_rate" class="form-label">RD + Hol. OT</label><input type="number" step="0.01" class="form-control" name="rd_hol_ot_rate" value="{{ old('rd_hol_ot_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="rd_sh_rate" class="form-label">RD + Special</label><input type="number" step="0.01" class="form-control" name="rd_sh_rate" value="{{ old('rd_sh_rate', '') }}"></div>
                      <div class="col-md-4 mb-3"><label for="rd_sh_ot_rate" class="form-label">RD + Special OT</label><input type="number" step="0.01" class="form-control" name="rd_sh_ot_rate" value="{{ old('rd_sh_ot_rate', '') }}"></div>
                    </div>
                  </div>
                </div>

                <!-- Benefits & Deductions Card -->
                <div class="card mb-4">
                  <div class="card-header">
                    Benefits & Deductions
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mb-3"><label for="cash_bond" class="form-label">Cash Bond</label><input type="number" step="0.01" class="form-control" name="cash_bond" value="{{ old('cash_bond', '200.00') }}"></div>
                      <div class="col-md-6 mb-3"><label for="sil" class="form-label">Service Incentive Leave</label><input type="number" step="0.01" class="form-control" name="sil" value="{{ old('sil', '0.00') }}"></div>
                      <div class="col-md-6 mb-3"><label for="ecola" class="form-label">ECOLA</label><input type="number" step="0.01" class="form-control" name="ecola" value="{{ old('ecola', '0.00') }}"></div>
                      <div class="col-md-6 mb-3"><label for="retirement_pay" class="form-label">Retirement Pay</label><input type="text" class="form-control" name="retirement_pay" value="{{ old('retirement_pay', '') }}"></div>
                      <div class="col-md-12 mb-3"><label for="thirteenth_month_pay" class="form-label">13th Month Pay</label><input type="text" class="form-control" name="thirteenth_month_pay" value="{{ old('thirteenth_month_pay', '') }}"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-3 text-end">
              <button type="submit" class="btn btn-primary">Submit Form</button>
              <button data-bs-dismiss="modal" class="btn btn-label-danger">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
