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
	 * @var string $VALID_INVITEECITY
	 **/
	protected $VALID_INVITEECITY = "Burque";
	/**
	 * country of the invitee
	 * @var string $VALID_INVITEECOUNTRY
	 **/
	 protected $VALID_INVITEECOUNTRY = "US";
	/**
	 * email of the invitee
	 * @var string $VALID_INVITEEEMAIL
	 **/
	protected $VALID_INVITEEEMAIL = "arlo@senate.romulan";
	/**
	 * name of the invitee
	 * @var string $VALID_INVITEENAME
	 **/
	protected $VALID_INVITEENAME = "Senator Arlo";
	/**
	 * phone of the invitee
	 * @var string $VALID_INVITEEPHONE
	 **/
	protected $VALID_INVITEEPHONE = "+15055551212";
	/**
	 * state of the invitee
	 * @var string $VALID_INVITEESTATE
	 **/
	protected $VALID_INVITEESTATE = "NM";
	/**
	 * address line 1 of the invitee
	 * @var string $VALID_INVITEESTREET1
	 **/
	protected $VALID_INVITEESTREET1 = "3701 Carlisle Blvd NE";
	/**
	 * address line 2 of the invitee
	 * @var string $VALID_INVITEESTREET2
	 **/
	protected $VALID_INVITEESTREET2 = "Senator Arlo's Nap Chamber";
	/**
	 * ZIP code of the invitee
	 * @var string $VALID_INVITEEZIP
	 **/
	 protected $VALID_INVITEEZIP = "87110";

	//---------------------------DEFAULT OBJECT-------------------------------//
	/**
	 * browser of the rsvp
	 * @var string $VALID_RSVPBROWSER
	 **/
	protected $VALID_RSVPBROWSER = "Netscape 1.0a";
	/**
	 * comment of the rsvp
	 * @var string $VALID_RSVPCOMMENT
	 **/
	protected $VALID_RSVPCOMMENT = "Senator Arlo requires a nap corner";
	/**
	 * IP address of the rsvp
	 * @var string $VALID_RSVPIPADDRESS
	 **/
	protected $VALID_RSVPIPADDRESS = "2600::deaf:beef:cafe";
	/**
	 * number of people of the rsvp
	 * @var string $VALID_RSVPNUMPEOPLE
	 **/
	protected $VALID_RSVPNUMPEOPLE = 42;
	/**
	 * timestamp of the rsvp
	 * @var string $VALID_RSVPTIMESTAMP
	 **/
	protected $VALID_RSVPTIMESTAMP = null;
	//---------------------------SECOND OBJECT--------------------------------//
	/**
	 * browser of the rsvp
	 * @var string $VALID_RSVPBROWSER2
	 **/
	protected $VALID_RSVPBROWSER2 = "Mosiac 95 Deluxe Edition";
	/**
	 * comment of the rsvp
	 * @var string $VALID_RSVPCOMMENT2
	 **/
	protected $VALID_RSVPCOMMENT2 = "Senator Arlo is on a back paws only diet";
	/**
	 * IP address of the rsvp
	 * @var string $VALID_RSVPIPADDRESS2
	 **/
	protected $VALID_RSVPIPADDRESS2 = null;
	/**
	 * number of people of the rsvp
	 * @var string $VALID_RSVPNUMPEOPLE2
	 **/
	protected $VALID_RSVPNUMPEOPLE2 = 16;
	/**
	 * timestamp of the rsvp
	 * @var string $VALID_RSVPTIMESTAMP2
	 **/
	protected $VALID_RSVPTIMESTAMP2 = null;
	//------------------------GENERATED OBJECTS-------------------------------//
	/**
	 * token of the invitee
	 * @var string $VALID_INVITEETOKEN
	 **/
	protected $VALID_INVITEETOKEN = null;
	/**
	 * Invitee for the Rsvp
	 * @var Invitee $invitee
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
		$this->invitee = new Invitee(null, $this->VALID_INVITEECITY, $this->VALID_INVITEECOUNTRY, $this->VALID_INVITEEEMAIL, $this->VALID_INVITEENAME, $this->VALID_INVITEEPHONE, $this->VALID_INVITEESTATE, $this->VALID_INVITEESTREET1, $this->VALID_INVITEESTREET2, $this->VALID_INVITEETOKEN, $this->VALID_INVITEEZIP);
		$this->invitee->insert($this->getPDO());
	}

	/**
	 * test inserting a valid Rsvp and verify that the actual mySQL data matches
	 **/
	public function testInsertValidRsvp() {
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
		$this->assertEquals($pdoRsvp->getRsvpTimestamp(), $this->VALID_RSVPTIMESTAMP);
	}

	/**
	 * test inserting a Rsvp that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidRsvp() {
		// create a Invitee with a non null invitee id and watch it fail
		$rsvp = new Rsvp(InvitersvpTest::INVALID_KEY, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->insert($this->getPDO());
	}

	/**
	 * test inserting an Rsvp, editing it, and then updating it
	 **/
	public function testUpdateValidRsvp() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert to into mySQL
		$rsvp = new Rsvp(null, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->insert($this->getPDO());

		// edit the Rsvp and update it in mySQL
		$rsvp->setRsvpBrowser($this->VALID_RSVPBROWSER2);
		$rsvp->setRsvpComment($this->VALID_RSVPCOMMENT2);
		$rsvp->setRsvpIpAddress($this->VALID_RSVPIPADDRESS2);
		$rsvp->setRsvpNumPeople($this->VALID_RSVPNUMPEOPLE2);
		$rsvp->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoRsvp = Rsvp::getRsvpByRsvpId($this->getPDO(), $rsvp->getRsvpId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertEquals($pdoRsvp->getRsvpInviteeId(), $this->invitee->getInviteeId());
		$this->assertEquals($pdoRsvp->getRsvpBrowser(), $this->VALID_RSVPBROWSER2);
		$this->assertEquals($pdoRsvp->getRsvpComment(), $this->VALID_RSVPCOMMENT2);
		$this->assertEquals($pdoRsvp->getRsvpIpAddress(), inet_ntop($this->VALID_RSVPIPADDRESS2));
		$this->assertEquals($pdoRsvp->getRsvpNumPeople(), $this->VALID_RSVPNUMPEOPLE2);
		$this->assertEquals($pdoRsvp->getRsvpTimestamp(), $this->VALID_RSVPTIMESTAMP2);
	}

	/**
	 * test updating a Rsvp that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidRsvp() {
		// create a Invitee with a non null invitee id and watch it fail
		$rsvp = new Rsvp(null, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->update($this->getPDO());
	}

	/**
	 * test inserting an Rsvp and deleting it
	 **/
	public function testDeleteValidRsvp() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert to into mySQL
		$rsvp = new Rsvp(null, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->insert($this->getPDO());

		// delete the Rsvp from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$rsvp->delete($this->getPDO());

		// grab the data from mySQL and enforce the Rsvp does not exist
		$pdoRsvp = Rsvp::getRsvpByRsvpId($this->getPDO(), $rsvp->getRsvpId());
		$this->assertNull($pdoRsvp);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("rsvp"));
	}

	/**
	 * test deleting a Rsvp that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidRsvp() {
		// create a Invitee with a non null invitee id and watch it fail
		$rsvp = new Rsvp(null, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->delete($this->getPDO());
	}

	/**
	 * test grabbing all Rsvps
	 **/
	public function testGetAllValidRsvps() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("rsvp");

		// create a new Rsvp and insert to into mySQL
		$rsvp = new Rsvp(null, $this->invitee->getInviteeId(), $this->VALID_RSVPBROWSER, $this->VALID_RSVPCOMMENT, $this->VALID_RSVPIPADDRESS, $this->VALID_RSVPNUMPEOPLE);
		$rsvp->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Rsvp::getAllRsvps($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("rsvp"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Io\\Deepdivedylan\\Invitersvp\\Rsvp", $results);

		// grab the result from the array and validate it
		$pdoRsvp = $results[0];
		$this->assertEquals($pdoRsvp->getRsvpInviteeId(), $this->invitee->getInviteeId());
		$this->assertEquals($pdoRsvp->getRsvpBrowser(), $this->VALID_RSVPBROWSER);
		$this->assertEquals($pdoRsvp->getRsvpComment(), $this->VALID_RSVPCOMMENT);
		$this->assertEquals($pdoRsvp->getRsvpIpAddress(), $this->VALID_RSVPIPADDRESS);
		$this->assertEquals($pdoRsvp->getRsvpNumPeople(), $this->VALID_RSVPNUMPEOPLE);
		$this->assertEquals($pdoRsvp->getRsvpTimestamp(), $this->VALID_RSVPTIMESTAMP);
	}
}
