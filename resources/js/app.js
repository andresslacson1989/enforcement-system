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
//Pusher.logToConsole = true;
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
                 <a href="${notificationData.link}" class="list-group-item list-group-item-action dropdown-notifications-item notification-link border-0" data-notification-id="${notificationData.notification_id}">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-info"><i class="ti ti-bell"></i></span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="small mb-1">${notificationData.title}</h6>
                        <small class="mb-1 d-block text-body">${notificationData.message}</small>
                        <small class="text-body-secondary">${notificationData.formatted_date}</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                          <span class="badge badge-dot"></span> <br>
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
  window.Echo.private('users.' + window.current_user_id).listen('NotificationSent', e => {
    updateNotifications(e);
  });
}

//Mark Notifications as read when clicked
// Listen on a static parent (like 'document') for a 'click' on a '.notification-link'
$(document).on('click', '.notification-link', function (event) {
  // 1. Prevent the browser from immediately going to the link's href
  event.preventDefault();

  // 'this' correctly refers to the .notification-link that was clicked
  const notificationId = this.dataset.notificationId;
  const originalUrl = this.href;
  const csrfToken = $('meta[name="csrf-token"]').attr('content'); // A slightly more "jQuery" way to get the token

  // 2. Send the request to your new endpoint
  fetch(`/notifications/${notificationId}/read`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Content-Type': 'application/json',
      Accept: 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) {
        console.error('Failed to mark notification as read.');
      }
      // 3. IMPORTANT: You'll need to uncomment the line below to navigate
      //    to the link's destination after the API call.
      window.location.href = originalUrl;
    })
    .catch(error => {
      console.error('Error:', error);
      // Also uncomment this line to navigate even if the API call fails
      window.location.href = originalUrl;
    });
});
