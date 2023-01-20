self.addEventListener('notificationclick', (event) => {
  event.waitUntil(
    clients
      .matchAll({
        type: "window",
      })
      .then((clientList) => {
        if (event.notification.actions[0].action === 'verTicket') {
          for (const client of clientList) {
            if (client.url === `/tickets/${event.notification.data.ticket}` && "focus" in client) {
              client.focus();
              break;
            }
          }
          /* let ticket_id = event.notification.data.ticket; */
          if (clients.openWindow)
            fetch(`/tickets/respuestas/${event.notification.data.ticket}`, {
              method: 'GET',
              headers: {
                'Content-Type': 'application/json'
              }
            }).then((res) => {
              return res.json();
            }).then((res) => {
              console.log(res)
            }).catch((err) => {
              console.log(err)
            });
          return clients.openWindow(`/tickets/${event.notification.data.ticket}`);
        } else {
          for (const client of clientList) {
            if (client.url === "/" && "focus" in client) {
              client.focus();
              break;
            }
          }
          if (clients.openWindow) return clients.openWindow("/tickets");
        }
      })
  );
});

self.addEventListener('push', function (e) {
  if (!(self.Notification && self.Notification.permission === 'granted')) {
    //notifications aren't supported or permission not granted!
    return;
  }
  if (e.data) {
    var msg = e.data.json();
    console.log(msg)
    e.waitUntil(self.registration.showNotification(msg.title, {
      body: msg.body,
      icon: msg.icon,
      actions: msg.actions,
      data: msg.data
    }));
  }
});