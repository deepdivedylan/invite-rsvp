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

	//sanitize input
	$inviteeToken = filter_input(INPUT_GET, "inviteeToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// handle POST requests
	if($method === "POST") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		// make sure invitee token is available (required field)
		if(empty($inviteeToken) === true) {
			throw(new \InvalidArgumentException("No invitee token for Rsvp", 405));
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
