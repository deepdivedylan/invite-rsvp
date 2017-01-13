<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 2) .  "/classes/autoload.php");
require_once("/etc/apache2/encrypted-config/encrypted-config.php");

use Io\Deepdivedylan\Invitersvp\Invitee;

$inviteTemplate = <<< EOF
<html>
	<head>
		<style>
			body {
				font-family: "FreeSans";
				text-align: center;
			}
			.firstPage {
				padding-top: 1.25in;
			}
		</style>
	</head>
	<body>
		<div class="firstPage">
			<h2>__INVITEE__</h2>
			<p>
				The pleasure of your company is requested<br />
				for the sacred union of
			</p>
			<h1>
				Dylan<br />
				&amp;<br />
				Tony
			</h1>
			<p>
				The celebration of love will be on<br />
				April Eight<br />
				Two Thousand Seventeen<br />
				Eleven O&rsquo;Clock in the morning<br />
				<em>reception to immediately follow</em>
			</p>
			<p>
				First Unitarian Church<br />
				3701 Carlisle Boulevard<br />
				Albuquerue, NM
			</p>
		</div>
		<pagebreak>
		<div>
			<h2>Attire</h2>
			<p>Casual attire is acceptable.</p>
			<h2>Lunch &amp; Drinks</h2>
			<p>
				Lunch and free drinks will be served immediately following the<br />
				ceremony in the social hall adjacent to the sanctuary.</p>
			<h2>Accomodations</h2>
			<p>
				Please call the Crowne Plaza Hotel in Albuquerue<br />
				and ask for the McDonald-Ration discounted room rate.<br />
				Or visit http://bit.ly/TonyDylanHotel</p>
			<h2>Registry</h2>
			<p>
				Gift Registry is at Amazon.<br />
				http://bit.ly/TonyDylanRegistry
			</p>
			<h2>RSVP</h2>
			<p>
				Scan this QR Code to immediately RSVP.<br />
				Or, simply mail the enclosed envelope by March 24<sup>th</sup>.
			</p>
			<p><barcode code="__URL__" type="QR" /></p>
		</div>
	</body>
</html>
EOF;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/encrypted-config/invitersvp.ini");

	$invitees = Invitee::getAllInvitees($pdo);
	foreach($invitees as $invitee) {
		// assemble the invite content
		$urlglue = "https://invitersvp.deepdivedylan.io/rsvp/" . $invitee->getInviteeToken();
		$inviteContent = str_replace("__INVITEE__", $invitee->getInviteeName(), $inviteTemplate);
		$inviteContent = str_replace("__URL__", $urlglue, $inviteContent);

		// save the PDF
		$invitePdf = new mPDF("UTF-8", [139.7, 196.85]);
		$invitePdf->WriteHTML($inviteContent);
		$invitePdf->Output(__DIR__ . "/invites/invite-" . $invitee->getInviteeId() . ".pdf", "F");
	}
} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . PHP_EOL;
} catch(TypeError $typeError) {
	echo "Type Error: " . $exception->getMessage() . PHP_EOL;
}
