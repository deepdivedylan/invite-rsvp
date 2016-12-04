<?php

namespace Io\Deepdivedylan\Invitersvp;

/**
 * Invitee RSVP Information
 *
 * Basic container class for RSVP information. Each RSVP contains anti-abuse
 * meta data.
 *
 * @author Dylan McDonald <dylan@deepdivedylan.com>
 * @version 1.0.0
 **/
class Rsvp implements \JsonSerializable {
	use ValidateDate;

	/**
	 * id for this Rsvp; this is the primary key
	 * @var int $rsvpId
	 **/
	private $rsvpId;
	/**
	 * invitee id for this Rsvp; this is foreign key to Invitee
	 * @var int $rsvpInviteeId
	 **/
	private $rsvpInviteeId;
	/**
	 * comment for this Rsvp
	 * @var string $rsvpComment
	 **/
	private $rsvpComment;
	/**
	 * IP address for this Rsvp
	 * @var string $rsvpIpAddress
	 **/
	private $rsvpIpAddress;
	/**
	 * number of people for this Rsvp
	 * @var int $rsvpNumPeople
	 **/
	private $rsvpNumPeople;
	/**
	 * timestamp for this Rsvp
	 * @var \DateTime $rsvpTimestamp
	 **/
	private $rsvpTimestamp;

	/**
	 * accessor method for rsvp id
	 *
	 * @return int|null value of rsvp id
	 **/
	public function getRsvpId() {
		return($this->rsvpId);
	}

	/**
	 * accessor method for invitee id
	 *
	 * @return int value of invitee id
	 **/
	public function getRsvpInviteeId() {
		return($this->rsvpInviteeId);
	}

	/**
	 * accessor method for rsvp comment
	 *
	 * @return string|null value of rsvp comment
	 **/
	public function getRsvpComment() {
		return($this->rsvpComment);
	}

	/**
	 * accessor method for rsvp IP address
	 *
	 * @return string value of rsvp IP address in printable form
	 **/
	public function getRsvpIpAddress() {
		return(inet_ntop($this->rsvpIpAddress));
	}

	/**
	 * accessor method for rsvp number of people
	 *
	 * @return int value of rsvp number of people
	 **/
	public function getRsvpNumPeople() {
		return($this->rsvpNumPeople);
	}

	/**
	 * accessor method for rsvp timestamp
	 *
	 * @return \DateTime value of rsvp timestamp
	 **/
	public function getRsvpTimestamp() {
		return($this->rsvpTimestamp);
	}
}
