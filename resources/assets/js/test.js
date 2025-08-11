'use strict';

$(function () {
  window.Echo.private('private-notifications')
    .listen('.Notification', e => {
    $('#notification-container').html(e);
  });
});
