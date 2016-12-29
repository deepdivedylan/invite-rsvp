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

		//make sure tweet content is available (required field)
		if(empty($requestObject->tweetContent) === true) {
			throw(new \InvalidArgumentException ("No content for Tweet.", 405));
		}

		// make sure tweet date is accurate (optional field)
		if(empty($requestObject->tweetDate) === true) {
			$requestObject->tweetDate = new \DateTime();
		}

		//  make sure profileId is available
		if(empty($requestObject->profileId) === true) {
			throw(new \InvalidArgumentException ("No Profile ID.", 405));
		}

		//perform the actual put or post
		if($method === "PUT") {

			// retrieve the tweet to update
			$tweet = Tweet::getTweetByTweetId($pdo, $id);
			if($tweet === null) {
				throw(new RuntimeException("Tweet does not exist", 404));
			}

			// update all attributes
			$tweet->setTweetDate($requestObject->tweetDate);
			$tweet->setTweetContent($requestObject->tweetContent);
			$tweet->update($pdo);

			// update reply
			$reply->message = "Tweet updated OK";

		} else if($method === "POST") {

			// create new tweet and insert into the database
			$tweet = new Tweet(null, $requestObject->profileId, $requestObject->tweetContent, null);
			$tweet->insert($pdo);

			// update reply
			$reply->message = "Tweet created OK";
		}

	} else if($method === "DELETE") {
		verifyXsrf();

		// retrieve the Tweet to be deleted
		$tweet = Tweet::getTweetByTweetId($pdo, $id);
		if($tweet === null) {
			throw(new RuntimeException("Tweet does not exist", 404));
		}

		// delete tweet
		$tweet->delete($pdo);

		// update reply
		$reply->message = "Tweet deleted OK";
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
