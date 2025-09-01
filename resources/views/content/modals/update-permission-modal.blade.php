<div class="modal fade" id="update_permission_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="update_roles_permission_form" method="post" action="{{ url('/form/update-permission') }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel4">Permission</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 mb-4">
              <label for="name" class="form-label">Permission Name</label>
              <input
                type="text"
                class="form-control"
                placeholder="Name"
                aria-label="Name"
                aria-describedby="permission_name"
                id="update_permission_name"
                name="update_permission_name"
                required
              />
            </div>
            <div class="col-12">
              <div class="maxLength-wrapper">
                <label for="update_permission_group" class="form-label">Set To Group</label>
                <select id="update_permission_group"
                        name="update_permission_group"
                        class="selectpicker w-100"
                        data-style="btn-default">
                  @foreach($permissions as $key => $item)
                    @if($key == 'Not Used')
                      @continue;
                    @endif
                    <option value="{{ $key }}">{{ ucwords($key) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="update_role_permissions">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
