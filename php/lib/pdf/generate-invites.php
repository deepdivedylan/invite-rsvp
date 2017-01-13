<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 2) .  "/classes/autoload.php");
require_once("/etc/apache2/encrypted-config/encrypted-config.php");

use Io\Deepdivedylan\Invitersvp\Invitee;

/**
 * formats an envelope
 *
 * @param string $envelopeTemplate envelope HTML template
 * @param Invitee $invitee invitee's address to format
 * @return string formatted envelope
 **/
function formatEnvelope(string $envelopeTemplate, Invitee $invitee) : string {
	$inviteeAddress = $invitee->getInviteeStreet1();
	if(empty($invitee->getInviteeStreet2()) === false) {
		$inviteeAddress = $inviteeAddress .  "<br />" . PHP_EOL . $invitee->getInviteeStreet2();
	}

	// assemble the envelope
	$envelopeContent = str_replace("__INVITEE-NAME__", $invitee->getInviteeName(), $envelopeTemplate);
	$envelopeContent = str_replace("__INVITEE-ADDRESS__", $inviteeAddress, $envelopeContent);
	$envelopeContent = str_replace("__INVITEE-CITY__", $invitee->getInviteeCity(), $envelopeContent);
	$envelopeContent = str_replace("__INVITEE-STATE__", $invitee->getInviteeState(), $envelopeContent);
	$envelopeContent = str_replace("__INVITEE-ZIP__", $invitee->getInviteeZip(), $envelopeContent);

	return($envelopeContent);
}

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

$inviteEnvelopeTemplate = <<< EOF
<html>
	<head>
	<style>
		body {
			font-family: "FreeSans";
		}
		.inviteeAddress {
			margin-left: 3.0in;
			margin-top: 1.5in;
		}
		.senderAddress {
			margin-left: 0.125in;
			margin-top: 0.125in;
			width: 2.5in;
			height: 1.5in;
		}
		@page {
			margin: 0px;
		}
	</style>
	</head>
	<body>
		<div>
			<p class="senderAddress">
				Tony Ration &amp; Dylan McDonald<br />
				215 Lead Ave SW #1310<br />
				Albuquerue, NM 87102
			</p>
			<p class="inviteeAddress">
				__INVITEE-NAME__<br />
				__INVITEE-ADDRESS__<br />
				__INVITEE-CITY__, __INVITEE-STATE__ __INVITEE-ZIP__
			</p>
		</div>
	</body>
</html>
EOF;

$rsvpTemplate = <<< EOF
<html>
	<head>
	<style>
		body {
			font-family: "FreeSans";
		}
		.blank {
			text-decoration: underline;
		}
		.container {
			padding-top: 4.5in;
			padding-left: 1.0in;
		}
		@page {
			margin: 0px;
		}
	</style>
	</head>
	<body>
		<div class="container">
			<h1>__INVITEE__</h1>
			<p>&#x25a2; is delighted to join you for <span class="blank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> people</p>
			<p>&#x25a2; declines with regrets</p>
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

		// create and save the invitee envelope PDF
		$inviteEnvelopePdf = new mPDF("UTF-8", [203.2, 146.05]);
		$inviteEnvelopePdf->WriteHTML(formatEnvelope($inviteEnvelopeTemplate, $invitee));
		$inviteEnvelopePdf->Output(__DIR__ . "/invite-envelopes/invite-envelope-" . $invitee->getInviteeId() . ".pdf", "F");

		// assemble the rsvp content
		$rsvpContent = str_replace("__INVITEE__", $invitee->getInviteeName(), $rsvpTemplate);

		// save the PDF
		$rsvpPdf = new mPDF("UTF-8", [120.65, 171.45]);
		$rsvpPdf->WriteHTML($rsvpContent);
		$rsvpPdf->Output(__DIR__ . "/rsvps/rsvp-" . $invitee->getInviteeId() . ".pdf", "F");
	}

	// create a ZIP file with the output files
	$zip = new \ZipArchive();
	if(($zipStatus = $zip->open(dirname(__DIR__, 3) . "/invitersvp.zip", \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) !== true) {
		throw(new \RuntimeException("unable to create ZIP file: $zipStatus", 500));
	}

	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__), RecursiveIteratorIterator::SELF_FIRST);
	foreach($files as $file) {
		$file = str_replace("\\", "/", $file);
		// Ignore "." and ".." folders
		if(in_array(substr($file, strrpos($file, "/") + 1), [".", ".."]) === true) {
			continue;
		}

		$file = realpath($file);
		if(is_dir($file) === true) {
			$zip->addEmptyDir(str_replace(__DIR__ . "/", "", $file . "/"));
		}
		else if(is_file($file) === true && substr($file, -4) === ".pdf") {
			$zip->addFromString(str_replace(__DIR__ . "/", "", $file), file_get_contents($file));
		}
	}
	$zip->close();
} catch(Exception $exception) {
	echo "Exception: " . $exception->getMessage() . PHP_EOL;
} catch(TypeError $typeError) {
	echo "Type Error: " . $typeError->getMessage() . PHP_EOL;
}
