'use strict';
document.addEventListener('DOMContentLoaded', function () {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // --- Panel Elements ---
  const rolesListContainer = document.getElementById('roles-list');
  const initialStatePanel = document.getElementById('initial-state');
  const editingStatePanel = document.getElementById('editing-state');

  // --- Form Elements ---
  const roleForm = document.getElementById('roleForm');
  const formTitle = document.getElementById('form-title');
  const roleIdInput = document.getElementById('role_id');
  const roleNameInput = document.getElementById('role_name');
  const roleDescriptionInput = document.getElementById('role_description');
  const selectAllCheckbox = document.getElementById('selectAll');
  const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

  // --- Buttons ---
  const addNewRoleBtn = document.getElementById('addNewRoleBtn');

  /**
   * Shows the editing panel and hides the initial placeholder.
   */
  const showEditingPanel = () => {
    initialStatePanel.classList.add('d-none');
    editingStatePanel.classList.remove('d-none');
  };

  /**
   * Resets the form to its default state and deselects any active role.
   */
  const resetForm = () => {
    roleForm.reset();
    permissionCheckboxes.forEach(cb => (cb.checked = false));
    selectAllCheckbox.checked = false;
    roleIdInput.value = '';
    // Remove active class from all roles
    document.querySelectorAll('.role-item.active').forEach(item => item.classList.remove('active'));
  };

  // --- Event Listener for Role Selection ---
  rolesListContainer.addEventListener('click', function (e) {
    e.preventDefault();
    const roleItem = e.target.closest('.role-item');
    if (!roleItem) return;

    resetForm(); // Reset form before populating

    // Set active class on the clicked role
    roleItem.classList.add('active');

    // Populate the form with data from the clicked role's data attributes
    formTitle.textContent = 'Edit Role';
    roleIdInput.value = roleItem.dataset.roleId;
    roleNameInput.value = roleItem.dataset.roleName;
    roleDescriptionInput.value = roleItem.dataset.roleDescription;

    const rolePermissions = JSON.parse(roleItem.dataset.rolePermissions);
    permissionCheckboxes.forEach(checkbox => {
      checkbox.checked = rolePermissions.includes(checkbox.value);
    });

    // Update the "Select All" checkbox state
    selectAllCheckbox.checked = rolePermissions.length === permissionCheckboxes.length;

    showEditingPanel();
  });

  // --- Event Listener for "Add New Role" Button ---
  addNewRoleBtn.addEventListener('click', function () {
    resetForm();
    formTitle.textContent = 'Add a New Role';
    showEditingPanel();
    roleNameInput.focus();
  });

  // --- Checkbox Logic ---
  selectAllCheckbox.addEventListener('change', function () {
    permissionCheckboxes.forEach(cb => (cb.checked = this.checked));
  });

  permissionCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
      selectAllCheckbox.checked = allChecked;
    });
  });

  // --- Form Submission Logic (using modern Fetch API) ---
  roleForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const isEdit = !!roleIdInput.value;
    const url = isEdit ? `/form/update-roles` : '/form/roles';
    // Note: For a true RESTful approach, the method for update should be 'PUT' or 'PATCH'.
    // We are using 'POST' here to match your original AJAX setup.
    const method = 'POST';

    // Show loading indicator
    Swal.fire({
      title: 'Please Wait!',
      text: 'Processing your request...',
      allowOutsideClick: false,
      showConfirmButton: false,
      willOpen: () => {
        Swal.showLoading();
      }
    });

    // Prepare form data
    const formData = new FormData(this);
    const data = {};

    // Convert FormData to a plain object
    formData.forEach((value, key) => {
      // This logic handles the 'permissions[]' array correctly
      if (key.endsWith('[]')) {
        const arrayKey = key.slice(0, -2);
        if (!data[arrayKey]) {
          data[arrayKey] = [];
        }
        data[arrayKey].push(value);
      } else {
        data[key] = value;
      }
    });

    // **IMPORTANT**: This part remaps keys to match your backend expectations for updates.
    if (isEdit) {
      data.role_description = data.description;
      data.role_permissions = data.permissions;
      delete data.description;
      delete data.permissions;
    }

    try {
      const response = await fetch(url, {
        method: method,
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          Accept: 'application/json'
        },
        body: JSON.stringify(data)
      });

      const result = await response.json();

      if (!response.ok) {
        // If response is not ok, create an error object to be caught by the catch block
        const error = new Error('HTTP status ' + response.status);
        error.response = result;
        error.status = response.status;
        throw error;
      }

      // Handle success
      if (result.message === 'Success') {
        Swal.fire({
          icon: 'success',
          title: result.text || (isEdit ? 'Role Updated!' : 'Role Created!'),
          showConfirmButton: false,
          timer: 1500
        }).then(() => {
          location.reload();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: result.text || 'An unknown error occurred.'
        });
      }
    } catch (error) {
      // Handle network errors and failed HTTP statuses
      let errorMsg = 'Could not connect to the server.';

      // Specifically handle 422 Validation Errors from Laravel
      if (error.status === 422) {
        const errors = error.response.errors;
        errorMsg = Object.values(errors)
          .map(e => e[0])
          .join('<br>');
      }

      Swal.fire({
        icon: 'error',
        title: 'Submission Failed',
        html: errorMsg
      });
    }
  });
});
