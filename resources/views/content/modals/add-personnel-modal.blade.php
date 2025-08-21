<div class="modal fade" id="add_personnel_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel4">Add New Detachment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container mt-4">
          <form method="POST" action="/detachments/add-personnel" id="add_personnel_form">
            @csrf
            <input type="hidden" value="{{ $detachment->id }}" id="detachment_id" name="detachment_id">
            <div class="modal-body">
              <div class="mb-3">
                <label for="user_id" class="form-label">Select Personnel</label>
                <select
                  class="selectpicker"
                  id="user_id"
                  name="user_id[]"
                  data-width="100%"
                  data-live-search="true"
                  title="Choose a user to add..."
                  required
                  multiple
                >
                  @forelse ($personnel as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} {{ $item->getRoleNames() }}</option>
                    @empty
                    <option value="">No data found</option>
                  @endforelse
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add to Roster</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
