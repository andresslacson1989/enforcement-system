import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

// Make jQuery available globally
//window.Pusher = Pusher;
Pusher.logToConsole = true;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: true
});

// window.Echo.channel('notifications')
//   // Listen for the 'NotificationSent' event
//   .listen('NotificationSent', (e) => {
//     alert('Notification Sent');
//     const message = e.message;
//
//     // Use jQuery to add the notification to the DOM
//     const notification = $(`<div class="alert alert-primary">${message}</div>`);
//     $('#notification-container').prepend(notification);
//   });


/**
 * Main function to handle new notifications and update the UI.
 * @param {object} notificationData The JSON data from the Pusher event.
 */
function updateNotifications(notificationData) {
  console.log(notificationData);
  // --- 1. Update the notification count ---
  let countElement = $('#notification-count');
  let currentCount = parseInt(countElement.text().replace(' New', '')) || 0;

  if (notificationData) {
      currentCount++;
    countElement.text(currentCount + ' New');
    $('#notification-bell-badge').removeClass('d-none');

    // --- 2. Add the new notification to the list ---
    let notificationList = $('#notification-list');

    const newNotificationItem = `
                  <a href="/form/view/${notificationData.form_type}/${notificationData.form_id}" class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-info"><i class="ti ti-check"></i></span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="small mb-1">${notificationData.message}</h6>
                            <small class="mb-1 d-block text-body">New submission received.</small>
                            <small class="text-body-secondary">${notificationData.formatted_date}</small>
                        </div>
                        <div class="flex-shrink-0 dropdown-notifications-actions">
                            <span class="badge badge-dot"></span>
                        </div>
                    </div>
                </a>
            `;

    notificationList.prepend(newNotificationItem);

    // --- 3. Enforce the 10-item limit ---
    let allNotifications = notificationList.find('li');
    if (allNotifications.length > 10) {
      $(allNotifications[allNotifications.length - 1]).remove();
    }
  }
}

// --- Laravel Echo Listener Setup (Using Private Channel) ---
if (typeof window.Echo !== 'undefined') {
  // IMPORTANT: Change from .channel() to .private()
  // The channel name should match the one in your broadcastOn() method.
  window.Echo.private('users.' + window.current_user_id)
    .listen('NotificationSent', (e) => {
      updateNotifications(e);
    });
}
