'use strict';

document.addEventListener('DOMContentLoaded', function () {
  // --- Panel Elements ---
  const roles_list_container = document.getElementById('roles-list');
  const initial_state_panel = document.getElementById('initial-state');
  const editing_state_panel = document.getElementById('editing-state');
  // --- Form Elements ---
  const role_form = document.getElementById('roleForm');
  const form_title = document.getElementById('form-title');
  const role_id_input = document.getElementById('role_id');
  const role_name_input = document.getElementById('role_name');
  const role_description_input = document.getElementById('role_description');
  const select_all_checkbox = document.getElementById('selectAll');
  const permission_checkboxes = document.querySelectorAll('.permission-checkbox');

  // --- Buttons ---
  const add_new_role_btn = document.getElementById('addNewRoleBtn');

  /**
   * Shows the editing panel and hides the initial placeholder.
   */
  const showEditingPanel = () => {
    initial_state_panel.classList.add('d-none');
    editing_state_panel.classList.remove('d-none');
  };

  /**
   * Resets the form to its default state and deselects any active role.
   */
  const resetForm = () => {
    role_form.reset();
    permission_checkboxes.forEach(cb => (cb.checked = false));
    select_all_checkbox.checked = false;
    role_id_input.value = '';
    // Remove active class from all roles
    document.querySelectorAll('.role-item.active').forEach(item => item.classList.remove('active'));
  };

  // --- Event Listener for Role Selection ---
  if (roles_list_container) {
    roles_list_container.addEventListener('click', function (e) {
      e.preventDefault();
      const role_item = e.target.closest('.role-item');
      if (!role_item) return;

      resetForm(); // Reset form before populating

      // Set active class on the clicked role
      role_item.classList.add('active');

      // Populate the form with data from the clicked role's data attributes
      form_title.textContent = 'Edit Role';
      role_id_input.value = role_item.dataset.roleId;
      role_name_input.value = role_item.dataset.roleName;
      role_description_input.value = role_item.dataset.roleDescription;

      const role_permissions = JSON.parse(role_item.dataset.rolePermissions);
      permission_checkboxes.forEach(checkbox => {
        checkbox.checked = role_permissions.includes(checkbox.value);
      });

      // Update the "Select All" checkbox state
      select_all_checkbox.checked = role_permissions.length === permission_checkboxes.length;

      showEditingPanel();
    });
  }

  // --- Event Listener for "Add New Role" Button ---
  if (add_new_role_btn) {
    add_new_role_btn.addEventListener('click', function () {
      resetForm();
      form_title.textContent = 'Add a New Role';
      showEditingPanel();
      role_name_input.focus();
    });
  }

  // --- Checkbox Logic ---
  if (select_all_checkbox) {
    select_all_checkbox.addEventListener('change', function () {
      permission_checkboxes.forEach(cb => (cb.checked = this.checked));
    });
  }

  permission_checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      const all_checked = Array.from(permission_checkboxes).every(cb => cb.checked);
      if (select_all_checkbox) {
        select_all_checkbox.checked = all_checked;
      }
    });
  });

  // --- Form Submission Logic (using modern Fetch API) ---
  if (role_form) {
    role_form.addEventListener('submit', async function (e) {
      e.preventDefault();

      const is_edit = !!role_id_input.value;
      const url = is_edit ? `/form/update-roles` : '/form/roles';
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
      const form_data = new FormData(this);
      const data = {};

      // Convert FormData to a plain object
      form_data.forEach((value, key) => {
        if (key.endsWith('[]')) {
          const array_key = key.slice(0, -2);
          if (!data[array_key]) {
            data[array_key] = [];
          }
          data[array_key].push(value);
        } else {
          data[key] = value;
        }
      });

      if (is_edit) {
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
          const error = new Error('HTTP status ' + response.status);
          error.response = result;
          error.status = response.status;
          throw error;
        }

        if (result.message === 'Success') {
          Swal.fire({
            icon: 'success',
            title: result.text || (is_edit ? 'Role Updated!' : 'Role Created!'),
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
        let error_msg = 'Could not connect to the server.';
        if (error.status === 422) {
          const errors = error.response.errors;
          error_msg = Object.values(errors)
            .map(e => e[0])
            .join('<br>');
        }
        Swal.fire({
          icon: 'error',
          title: 'Submission Failed',
          html: error_msg
        });
      }
    });
  }
});
