<?php
// start session and enable XSRF protection
require_once(dirname(__DIR__) . "/php/lib/xsrf.php");
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
setXsrfCookie();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<base href="/" />

		<title>Invite RSVP Manager</title>
	</head>
	<body>
		<invite-rsvp-app>Loading&hellip;</invite-rsvp-app>
	</body>
</html>
