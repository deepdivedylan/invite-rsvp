<?php
namespace Io\Deepdivedylan\Invitersvp\Test;

use Io\Deepdivedylan\Invitersvp\Invitee;

// grab the project test parameters
require_once("InviteeTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");

class InviteeTest extends InvitersvpTest {
	//---------------------------DEFAULT OBJECT-------------------------------//
	/**
	 * city of the invitee
	 * @param string $VALID_INVITEECITY
	 **/
	protected $VALID_INVITEECITY = "Burque";
	/**
	 * email of the invitee
	 * @param string $VALID_INVITEEMAIL
	 **/
	protected $VALID_INVITEEMAIL = "arlo@senate.romulan";
	/**
	 * name of the invitee
	 * @param string $VALID_INVITEENAME
	 **/
	protected $VALID_INVITEENAME = "Senator Arlo";
	/**
	 * phone of the invitee
	 * @param string $VALID_INVITEEPHONE
	 **/
	protected $VALID_INVITEEPHONE = "+15055551212";
	/**
	 * state of the invitee
	 * @param string $VALID_INVITEESTATE
	 **/
	protected $VALID_INVITEESTATE = "NM";
	/**
	 * address line 1 of the invitee
	 * @param string $VALID_INVITEESTREET1
	 **/
	protected $VALID_INVITEESTREET1 = "3701 Carlisle Blvd NE";
	/**
	 * address line 2 of the invitee
	 * @param string $VALID_INVITEESTREET2
	 **/
	protected $VALID_INVITEESTREET2 = "Senator Arlo's Nap Chamber";
	/**
	 * ZIP code of the invitee
	 * @param string $VALID_INVITEEZIP
	 **/
	 protected $VALID_INVITEEZIP = "87110";

	//---------------------------SECOND OBJECT--------------------------------//
	/**
	 * city of the invitee
	 * @param string $VALID_INVITEECITY2
	 **/
	protected $VALID_INVITEECITY2 = "Los Lunas";
	/**
	 * email of the invitee
	 * @param string $VALID_INVITEEMAIL2
	 **/
	protected $VALID_INVITEEMAIL2 = "simon@senate.romulan";
	/**
	 * name of the invitee
	 * @param string $VALID_INVITEENAME2
	 **/
	protected $VALID_INVITEENAME2 = "Praetor Si'mon";
	/**
	 * phone of the invitee
	 * @param string $VALID_INVITEEPHONE2
	 **/
	protected $VALID_INVITEEPHONE2 = "+18188675309";
	/**
	 * state of the invitee
	 * @param string $VALID_INVITEESTATE2
	 **/
	protected $VALID_INVITEESTATE2 = "CA";
	/**
	 * address line 1 of the invitee
	 * @param string $VALID_INVITEESTREET12
	 **/
	protected $VALID_INVITEESTREET12 = "9550 Haskell Ave";
	/**
	 * address line 2 of the invitee
	 * @param string $VALID_INVITEESTREET22
	 **/
	protected $VALID_INVITEESTREET22 = "Praetor Si'mon's Hall of Fuzzy";
	/**
	 * ZIP code of the invitee
	 * @param string $VALID_INVITEEZIP2
	 **/
	 protected $VALID_INVITEEZIP2 = "91343";

	//-------------------------GENERATED TOKENS-------------------------------//
	/**
	 * token of the invitee
	 * @param string $VALID_INVITETOKEN
	 **/
	protected $VALID_INVITEETOKEN = null;
	/**
	 * token of the invitee
	 * @param string $VALID_INVITETOKEN2
	 **/
	protected $VALID_INVITEETOKEN2 = null;
}
