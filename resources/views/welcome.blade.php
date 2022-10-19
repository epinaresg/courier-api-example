<head>
	<title>Pusher Test</title>
	<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
	<script>

	  // Enable pusher logging - don't include this in production
	  Pusher.logToConsole = true;

	  var pusher = new Pusher('e56a15992170c3210520', {
		cluster: 'us2'
	  });

	  var channel = pusher.subscribe('public');
	  channel.bind('my-event', function(data) {
		alert(JSON.stringify(data));
	  });
	</script>
  </head>
  <body>
	<h1>Pusher Test</h1>
	<p>
	  Try publishing an event to channel <code>my-channel</code>
	  with event name <code>my-event</code>.
	</p>
  </body>
