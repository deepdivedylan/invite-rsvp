<?php
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once("/etc/apache2/encrypted-config/encrypted-config.php");

use Io\Deepdivedylan\Invitersvp\{Invitee, Rsvp};

$acceptMessage = "<h1>Thank You for RSVPing!</h1>
<p>Hi __NAME__,</p>
<p>Thank you for RSVPing for our wedding! We're so happy you can share our day of joy with us! We look forward to seeing you there. Mark your calendars for the big day on April 8th.</p>
<p>If you don't live in the Albuquerque area, you can get a special rate at the Crowne Plaza for $64 / night by calling <a href=\"tel:+15058842500\">505.884.2500</a> and saying you're part of the McDonald/Ration Wedding. You can also book online using the following link:<br />
<a href=\"https://www.crowneplaza.com/redirect?path=hd&brandCode=cp&localeCode=en&regionCode=1&hotelCode=ABQCP&_PMID=99801505&GPC=MRW&viewfullsite=true\">https://www.crowneplaza.com/redirect?path=hd&brandCode=cp&localeCode=en&regionCode=1&hotelCode=ABQCP&_PMID=99801505&GPC=MRW&viewfullsite=true</a><br />
Selecting the dates near our wedding (e.g., April 7th to April 9th) will automatically apply the group rate. Feel free to contact either of us if you have any questions.</p>
<p>We are registered at Amazon, which can be accessed using the following link:<br />
<a href=\"https://www.amazon.com/wedding/tony-ration-dylan-mcdonald-albuquerque-april-2017/registry/19NDQHAPUZZKD\">https://www.amazon.com/wedding/tony-ration-dylan-mcdonald-albuquerque-april-2017/registry/19NDQHAPUZZKD</a></p>
<p>Again, we're delighted you can make it! Let either of us know if you have any questions.</p>
<p>With love &lt;3</p>
<p>Tony &amp; Dylan</p>";

$rejectMessage = "<h1>Thank You for RSVPing!</h1>
<p>Hi __NAME__,</p>
<p>Thank you for RSVPing for our wedding! We regret that you cannot make it to share in our joy.</p>
<p>We are registered at Amazon, which can be accessed using the following link:<br />
<a href=\"https://www.amazon.com/wedding/tony-ration-dylan-mcdonald-albuquerque-april-2017/registry/19NDQHAPUZZKD\">https://www.amazon.com/wedding/tony-ration-dylan-mcdonald-albuquerque-april-2017/registry/19NDQHAPUZZKD</a></p>
<p>Again, we're sorry you cannot make it. Let either of us know if you have any questions.</p>
<p>With love &lt;3</p>
<p>Tony &amp; Dylan</p>";

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/encrypted-config/invitersvp.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$rsvpInviteeId = filter_input(INPUT_GET, "rsvpInviteeId", FILTER_VALIDATE_INT);

	// handle POST requests
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		// verify there's an admin logged in
		if(emtpy($_SESSION["login"]) === true) {
			throw(new InvalidArgumentException("not logged in", 401));
		}

		if(empty($id) === false) {
			$rsvp = Rsvp::getRvspByRsvpId($pdo, $id);
			$reply->data = $rsvp;
		} else if(empty($rsvpInviteeId) === false) {
			$rsvp = Rsvp::getRsvpByRsvpInviteeId($pdo, $rsvpInviteeId);
			$reply->data = $rsvp;
		} else {
			$rsvps = Rsvp::getAllRsvps($pdo)->toArray();
			$reply->data = $rsvps;
		}
	} else if($method === "POST" || $method === "PUT") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure invitee id is available (required field)
		if(empty($requestObject->rsvpInviteeId) === true) {
			throw(new \InvalidArgumentException("No invitee id for Rsvp", 405));
		}

		// make sure rsvp number of people is available (required field)
		if(empty($requestObject->rsvpNumPeople) === true) {
			throw(new \InvalidArgumentException("No number of people for Rsvp", 405));
		}

		// make sure rsvp comment is available (optional field)
		if(empty($requestObject->rsvpComment) === true) {
			$requestObject->rsvpComment = null;
		}

		// make sure invitee exists
		$invitee = Invitee::getInviteeByInviteeId($pdo, $requestObject->rsvpInviteeId);
		if($invitee === null) {
			throw(new \InvalidArgumentException("Invitee not found", 404));
		}

		// create new rsvp and insert into the database
		if($method === "POST") {
			$rsvp = new Rsvp(null, $invitee->getInviteeId(), $_SERVER["HTTP_USER_AGENT"], $requestObject->rsvpComment, $_SERVER["REMOTE_ADDR"], $requestObject->rsvpNumPeople, null);
			$rsvp->insert($pdo);
			$reply->message = "Rsvp created OK";
			// update the rsvp and update it in the database
		} else {
			$rsvp = Rsvp::getRsvpByRsvpId($pdo, $requestObject->rsvpId);
			if($rsvp === null) {
				throw(new \InvalidArgumentException("RSVP not found", 404));
			}
			$rsvp->setRsvpComment($requestObject->rsvpComment);
			$rsvp->setRsvpNumPeople($requestObject->rsvpNumPeople);
			$rsvp->update($pdo);
			$reply->message = "Rsvp updated OK";
		}

		// send the invitee an Email, if one was entered
		if($invitee->getInviteeEmail() !== null) {
			$swiftMessage = Swift_Message::newInstance();
			$swiftMessage->setFrom(["wedding@deepdivedylan.io" => "Dylan And Tony's Wedding"]);
			$swiftMessage->setTo([$invitee->getInviteeEmail() => $invitee->getInviteeName()]);
			$swiftMessage->setSubject("Dylan and Tony's Wedding RSVP");

			// generate message based on acceptance
			if($rsvp->getRsvpNumPeople() === 0) {
				$message = $rejectMessage;
			} else {
				$message = $acceptMessage;
			}
			$message = str_replace("__NAME__", $invitee->getInviteeName(), $message);


			// now send the message
			$swiftMessage->setBody($message, "text/html");
			$swiftMessage->addPart(html_entity_decode($message), "text/plain");
			$smtp = Swift_SmtpTransport::newInstance("localhost", 25);
			$mailer = Swift_Mailer::newInstance($smtp);
			$numSent = $mailer->send($swiftMessage, $failedRecipients);
			if($numSent !== 1) {
				// the $failedRecipients parameter passed in the send() method now contains contains an array of the Emails that failed
				throw(new RuntimeException("unable to send email"));
			}
		}
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}

	// update reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);
