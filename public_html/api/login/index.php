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
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// handle GET request
	if($method === "GET") {
		setXsrfCookie();
		$reply->data = new stdClass();
		$reply->data->loginStatus = (empty($_SESSION["login"]) === false);
	} else if($method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure login username is available (required field)
		if(empty($requestObject->loginUsername) === true) {
			throw(new \InvalidArgumentException("No username for Login", 405));
		}

		// make sure login password is available (required field)
		if(empty($requestObject->loginPassword) === true) {
			throw(new \InvalidArgumentException("No country for Invitee", 405));
		}

		// grab the login config
		$config = readConfig("/etc/apache2/encrypted-config/invitersvp.ini");
		$admins = json_decode($config["admins"]);

		// verify the user
		$loggedIn = false;
		$username = trim(filter_var($requestObject->loginUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
		if(empty($username) === true) {
			throw(new InvalidArgumentException("Username or password invalid", 401));
		}
		foreach ($admins as $admin) {
			if($admin->user === $username) {
				$hash = hash_pbkdf2("sha512", $requestObject->loginPassword, $admin->salt, 262144);
				$loggedIn = ($admin->hash === $hash);
				break;
			}
		}

		if($loggedIn === true) {
			$_SESSION["login"] = $username;
			$reply->message = "User logged in OK";
		} else {
			unset($_SESSION["login"]);
			throw(new InvalidArgumentException("Username or password invalid", 401));
		}

	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request", 400));
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
