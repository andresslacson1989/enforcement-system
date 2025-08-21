
<!-- Edit Detachment Modal -->
<div class="modal fade" id="edit_detachment_modal" tabindex="-1" aria-labelledby="edit_detachment_modal" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel4">Edit Detachment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form id="edit_detachment_form" action="/detachments/update/{{ $detachment->id }}" method="PUT">
            {{-- CSRF and Method Spoofing for Laravel --}}
            @csrf
            <div class="row">
              {{-- First Column --}}
              <div class="col-md-6">
                <!-- Basic Information Card -->
                <div class="card mb-4">
                  <div class="card-header">Basic Information</div>
                  <div class="card-body">
                    <div class="mb-3"><label for="name" class="form-label">Detachment Name</label><input type="text" class="form-control" id="name" name="name" required></div>
                    <div class="mb-3">
                      <label for="assigned_officer" class="form-label">Assigned Officer (Commander)</label>
                      <select
                        class="form-control selectpicker"
                        id="assigned_officer"
                        name="assigned_officer"
                        data-live-search="true"
                        data-style="btn-default">
                        @foreach ($officers as $officer)
                          <option value="{{ $officer->id }}"> {{ $officer->name }} </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <!-- Location Card -->
                <div class="card mb-4">
                  <div class="card-header">Location Details</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12 mb-3"><label for="street" class="form-label">Street</label><input type="text" class="form-control" id="street" name="street" required></div>
                      <div class="col-md-6 mb-3"><label for="city" class="form-label">City</label><input type="text" class="form-control" id="city" name="city" required></div>
                      <div class="col-md-6 mb-3"><label for="province" class="form-label">Province</label><input type="text" class="form-control" id="province" name="province" required></div>
                      <div class="col-md-6 mb-3"><label for="zip_code" class="form-label">Zip Code</label><input type="text" class="form-control" id="zip_code" name="zip_code" required></div>
                    </div>
                  </div>
                </div>
                <!-- Duty Configuration Card -->
                <div class="card mb-4">
                  <div class="card-header">Duty & Shift Configuration</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4 mb-3"><label for="hours_per_shift" class="form-label">Hours/Shift</label><input type="number" class="form-control" id="hours_per_shift" name="hours_per_shift"></div>
                      <div class="col-md-4 mb-3"><label for="max_hrs_duty" class="form-label">Max Hours Duty</label><input type="number" class="form-control" id="max_hrs_duty" name="max_hrs_duty"></div>
                      <div class="col-md-4 mb-3"><label for="max_ot" class="form-label">Max OT Hours</label><input type="number" class="form-control" id="max_ot" name="max_ot"></div>
                    </div>
                  </div>
                </div>
              </div>
              {{-- Second Column --}}
              <div class="col-md-6">
                <!-- Pay Rates Card -->
                <div class="card mb-4">
                  <div class="card-header">Pay Rate Configuration (Per Hour)</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-4 mb-3"><label for="hr_rate" class="form-label">Hourly</label><input type="number" step="0.01" class="form-control" id="hr_rate" name="hr_rate"></div>
                      <div class="col-md-4 mb-3"><label for="ot_rate" class="form-label">OT</label><input type="number" step="0.01" class="form-control" id="ot_rate" name="ot_rate"></div>
                      <div class="col-md-4 mb-3"><label for="nd_rate" class="form-label">Night Diff</label><input type="number" step="0.01" class="form-control" id="nd_rate" name="nd_rate"></div>
                      <div class="col-md-4 mb-3"><label for="rdd_rate" class="form-label">Rest Day</label><input type="number" step="0.01" class="form-control" id="rdd_rate" name="rdd_rate"></div>
                      <div class="col-md-4 mb-3"><label for="rdd_ot_rate" class="form-label">Rest Day OT</label><input type="number" step="0.01" class="form-control" id="rdd_ot_rate" name="rdd_ot_rate"></div>
                      <div class="col-md-4 mb-3"><label for="hol_rate" class="form-label">Holiday</label><input type="number" step="0.01" class="form-control" id="hol_rate" name="hol_rate"></div>
                      <div class="col-md-4 mb-3"><label for="hol_ot_rate" class="form-label">Holiday OT</label><input type="number" step="0.01" class="form-control" id="hol_ot_rate" name="hol_ot_rate"></div>
                      <div class="col-md-4 mb-3"><label for="sh_rate" class="form-label">Special Hol.</label><input type="number" step="0.01" class="form-control" id="sh_rate" name="sh_rate"></div>
                      <div class="col-md-4 mb-3"><label for="sh_ot_rate" class="form-label">Special Hol. OT</label><input type="number" step="0.01" class="form-control" id="sh_ot_rate" name="sh_ot_rate"></div>
                      <div class="col-md-4 mb-3"><label for="rd_hol_rate" class="form-label">RD + Hol.</label><input type="number" step="0.01" class="form-control" id="rd_hol_rate" name="rd_hol_rate"></div>
                      <div class="col-md-4 mb-3"><label for="rd_hol_ot_rate" class="form-label">RD + Hol. OT</label><input type="number" step="0.01" class="form-control" id="rd_hol_ot_rate" name="rd_hol_ot_rate"></div>
                      <div class="col-md-4 mb-3"><label for="rd_sh_rate" class="form-label">RD + Special</label><input type="number" step="0.01" class="form-control" id="rd_sh_rate" name="rd_sh_rate"></div>
                      <div class="col-md-4 mb-3"><label for="rd_sh_ot_rate" class="form-label">RD + Special OT</label><input type="number" step="0.01" class="form-control" id="rd_sh_ot_rate" name="rd_sh_ot_rate"></div>
                    </div>
                  </div>
                </div>
                <!-- Benefits & Deductions Card -->
                <div class="card mb-4">
                  <div class="card-header">Benefits & Deductions</div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 mb-3"><label for="cash_bond" class="form-label">Cash Bond</label><input type="number" step="0.01" class="form-control" id="cash_bond" name="cash_bond"></div>
                      <div class="col-md-6 mb-3"><label for="sil" class="form-label">SIL</label><input type="number" step="0.01" class="form-control" id="sil" name="sil"></div>
                      <div class="col-md-6 mb-3"><label for="ecola" class="form-label">ECOLA</label><input type="number" step="0.01" class="form-control" id="ecola" name="ecola"></div>
                      <div class="col-md-6 mb-3"><label for="retirement_pay" class="form-label">Retirement Pay</label><input type="text" class="form-control" id="retirement_pay" name="retirement_pay"></div>
                      <div class="col-md-12 mb-3"><label for="thirteenth_month_pay" class="form-label">13th Month Pay</label><input type="text" class="form-control" id="thirteenth_month_pay" name="thirteenth_month_pay"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="edit_detachment_form" class="btn btn-primary">Save Changes</button>
      </div>
    </div>
  </div>
</div>
