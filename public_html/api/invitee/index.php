<?php

require_once(dirname(__DIR__, 3) .  "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) .  "/php/lib/xsrf.php");
require_once("/etc/apache2/encrypted-config/encrypted-config.php");

use Io\Deepdivedylan\Invitersvp\Invitee;

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
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$inviteeToken = filter_input(INPUT_GET, "inviteeToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	// verify there's an admin logged in
	if(empty($_SESSION["login"]) === true) {
		throw(new \InvalidArgumentException("not logged in", 401));
	}

	// handle GET request - if id is present, that invitee is returned, otherwise all invitees are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific invitee or all invitees and update reply
		if(empty($id) === false) {
			$invitee = Invitee::getInviteeByInviteeId($pdo, $id);
			if($invitee !== null) {
				$reply->data = $invitee;
			}
		} else if(empty($inviteeToken) === false) {
			$invitee = Invitee::getInviteeByInviteeToken($pdo, $inviteeToken);
			if($invitee !== null) {
				$reply->data = $invitee;
			}
		} else {
			$invitees = Invitee::getAllInvitees($pdo)->toArray();
			if($invitees !== null) {
				$reply->data = $invitees;
			}
		}
	} else if($method === "PUT" || $method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure invitee city is available (required field)
		if(empty($requestObject->inviteeCity) === true) {
			throw(new \InvalidArgumentException("No city for Invitee", 405));
		}

		// make sure invitee country is available (required field)
		if(empty($requestObject->inviteeCountry) === true) {
			throw(new \InvalidArgumentException("No country for Invitee", 405));
		}

		// make sure invitee name is available (required field)
		if(empty($requestObject->inviteeName) === true) {
			throw(new \InvalidArgumentException("No name for Invitee", 405));
		}

		// make sure invitee state is available (required field)
		if(empty($requestObject->inviteeState) === true) {
			throw(new \InvalidArgumentException("No state for Invitee", 405));
		}

		// make sure invitee street 1 is available (required field)
		if(empty($requestObject->inviteeStreet1) === true) {
			throw(new \InvalidArgumentException("No street 1 for Invitee", 405));
		}

		// make sure invitee zip is available (required field)
		if(empty($requestObject->inviteeZip) === true) {
			throw(new \InvalidArgumentException("No ZIP for Invitee", 405));
		}

		//perform the actual put or post
		if($method === "PUT") {

			// retrieve the invitee to update
			$invitee = Invitee::getInviteeByInviteeId($pdo, $id);
			if($invitee === null) {
				throw(new RuntimeException("Invitee does not exist", 404));
			}

			// update all attributes - except token, which is immutable
			$invitee->setInviteeCity($requestObject->inviteeCity);
			$invitee->setInviteeCountry($requestObject->inviteeCountry);
			$invitee->setInviteeEmail($requestObject->inviteeEmail);
			$invitee->setInviteeName($requestObject->inviteeName);
			$invitee->setInviteePhone($requestObject->inviteePhone);
			$invitee->setInviteeState($requestObject->inviteeState);
			$invitee->setInviteeStreet1($requestObject->inviteeStreet1);
			$invitee->setInviteeStreet2($requestObject->inviteeStreet2);
			$invitee->setInviteeZip($requestObject->inviteeZip);
			$invitee->update($pdo);

			// update reply
			$reply->message = "Invitee updated OK";

		} else if($method === "POST") {

			// create new invitee and insert into the database
			$inviteeToken = bin2hex(random_bytes(16));
			$invitee = new Invitee(null, $requestObject->inviteeCity, $requestObject->inviteeCountry, $requestObject->inviteeEmail, $requestObject->inviteeName, $requestObject->inviteePhone, $requestObject->inviteeState, $requestObject->inviteeStreet1, $requestObject->inviteeStreet2, $inviteeToken, $requestObject->inviteeZip);
			$invitee->insert($pdo);

			// update reply
			$reply->message = "Invitee created OK";
		}
	} else if($method === "DELETE") {
		verifyXsrf();

		// retrieve the invitee to be deleted
		$invitee = Invitee::getInviteeByInviteeId($pdo, $id);
		if($invitee === null) {
			throw(new RuntimeException("Invitee does not exist", 404));
		}

		// delete invitee
		$invitee->delete($pdo);

		// update reply
		$reply->message = "Invitee deleted OK";
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
