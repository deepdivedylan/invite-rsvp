<?php

require_once(dirname(__DIR__, 3) .  "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) .  "/php/lib/xsrf.php");
require_once("/etc/apache2/encrypted-config/encrypted-config.php");

use Io\Deepdivedylan\Invitersvp\{Invitee, Rsvp};

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

	//sanitize input
	$inviteeToken = filter_input(INPUT_GET, "inviteeToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// handle POST requests
	if($method === "POST") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure rsvp number of people is available (required field)
		if(empty($requestObject->rsvpNumPeople) === true) {
			throw(new \InvalidArgumentException("No number of people for Rsvp", 405));
		}

		// make sure rsvp comment is available (optional field)
		if(empty($requestObject->rsvpComment) === true) {
			$requestObject->rsvpComment = null;
		}

		// make sure invitee exists
		$invitee = Invitee::getInviteeByInviteeToken($pdo, $inviteeToken);
		if($invitee === null) {
			throw(new \InvalidArgumentException("Invitee not found", 404));
		}

		// create new rsvp and insert into the database
		$rsvp = new Rsvp(null, $invitee->getInviteeId(), $_SERVER["HTTP_USER_AGENT"], $requestObject->rsvpComment, $_SERVER["REMOTE_ADDR"], $requestObject->rsvpNumPeople, null);
		$rsvp->insert($pdo);

		// update reply
		$reply->message = "Rsvp created OK";
	} else {
		if($method === "GET") {
			setXsrfCookie();
		}
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
