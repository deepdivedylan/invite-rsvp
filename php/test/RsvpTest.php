<?php
namespace Io\Deepdivedylan\Invitersvp\Test;

use Io\Deepdivedylan\Invitersvp\{Invitee, Rsvp};

// grab the project test parameters
require_once("InvitersvpTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");

class RsvpTest extends InvitersvpTest {
	//----------------------------INVITE OBJECT-------------------------------//
	/**
	 * city of the invitee
	 * @param string $VALID_INVITEECITY
	 **/
	protected $VALID_INVITEECITY = "Burque";
	/**
	 * email of the invitee
	 * @param string $VALID_INVITEEEMAIL
	 **/
	protected $VALID_INVITEEEMAIL = "arlo@senate.romulan";
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

	//---------------------------DEFAULT OBJECT-------------------------------//
	/**
	 * browser of the rsvp
	 * @param string $VALID_RSVPBROWSER
	 **/
	protected $VALID_RSVPBROWSER = "Netscape 1.0a";
	/**
	 * comment of the rsvp
	 * @param string $VALID_RSVPCOMMENT
	 **/
	protected $VALID_RSVPCOMMENT = "Senator Arlo requires a nap corner";
	/**
	 * IP address of the rsvp
	 * @param string $VALID_RSVPIPADDRESS
	 **/
	protected $VALID_RSVPIPADDRESS = "2600::deaf:beef:cafe";
	/**
	 * number of people of the rsvp
	 * @param string $VALID_RSVPNUMPEOPLE
	 **/
	protected $VALID_RSVPNUMPEOPLE = 42;
	/**
	 * timestamp of the rsvp
	 * @param string $VALID_RSVPTIMESTAMP
	 **/
	protected $VALID_RSVPTIMESTAMP = null;
	//---------------------------SECOND OBJECT--------------------------------//
	/**
	 * browser of the rsvp
	 * @param string $VALID_RSVPBROWSER2
	 **/
	protected $VALID_RSVPBROWSER2 = "Mosiac 95 Deluxe Edition";
	/**
	 * comment of the rsvp
	 * @param string $VALID_RSVPCOMMENT2
	 **/
	protected $VALID_RSVPCOMMENT2 = "Senator Arlo is on a back paws only diet";
	/**
	 * IP address of the rsvp
	 * @param string $VALID_RSVPIPADDRESS2
	 **/
	protected $VALID_RSVPIPADDRESS2 = null;
	/**
	 * number of people of the rsvp
	 * @param string $VALID_RSVPNUMPEOPLE2
	 **/
	protected $VALID_RSVPNUMPEOPLE2 = 16;
	/**
	 * timestamp of the rsvp
	 * @param string $VALID_RSVPTIMESTAMP2
	 **/
	protected $VALID_RSVPTIMESTAMP2 = null;
	//------------------------GENERATED OBJECTS-------------------------------//
	/**
	 * token of the invitee
	 * @param string $VALID_INVITEETOKEN
	 **/
	protected $VALID_INVITEETOKEN = null;
	/**
	 * Invitee for the Rsvp
	 * @param Invitee $invitee
	 **/
	protected $invitee = null;

	/**
	 * create dependent objects before running each test
	 **/
	public function setUp() {
		$this->VALID_RSVPTIMESTAMP = new \DateTime();
		$this->VALID_RSVPTIMESTAMP2 = new \DateTime();
		$this->VALID_RSVPIPADDRESS2 = chr(192) . chr(168) . chr(38) . chr(96);

		$this->VALID_INVITEETOKEN = bin2hex(random_bytes(16));
		$this->invitee = new Invitee(null, $this->VALID_INVITEECITY, $this->VALID_INVITEEEMAIL, $this->VALID_INVITEENAME, $this->VALID_INVITEEPHONE, $this->VALID_INVITEESTATE, $this->VALID_INVITEESTREET1, $this->VALID_INVITEESTREET2, $this->VALID_INVITEETOKEN, $this->VALID_INVITEEZIP);
		$this->invitee->insert($this->getPDO());
	}

	/**
	 * test inserting a valid Rsvp and verify that the actual mySQL data matches
	 **/
	public function testInsertValidInvitee() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert to into mySQL
		$rsvp = new Rsvp(null, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoRsvp = Rsvp::getRsvpByRsvpId($this->getPDO(), $rsvp->getRsvpId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertEquals($pdoRsvp->getRsvpInviteeId(), $this->invitee->getInviteeId());
		$this->assertEquals($pdoRsvp->getRsvpBrowser(), $this->VALID_RSVPBROWSER);
		$this->assertEquals($pdoRsvp->getRsvpComment(), $this->VALID_RSVPCOMMENT);
		$this->assertEquals($pdoRsvp->getRsvpIpAddress(), $this->VALID_RSVPIPADDRESS);
		$this->assertEquals($pdoRsvp->getRsvpNumPeople(), $this->VALID_RSVPNUMPEOPLE);
	}
}
