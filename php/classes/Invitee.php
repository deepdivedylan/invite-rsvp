<?php

namespace Io\Deepdivedylan\Invitersvp;

/**
 * Invitee Contact Information
 *
 * Basic container class for invitee information. Each invitee contains a token
 * for randomization of invite URLs to prevent information leakage.
 *
 * @author Dylan McDonald <dylan@deepdivedylan.com>
 * @version 1.0.0
 **/
class Invitee implements \JsonSerializable {
	/**
	 * id for this Invitee; this is the primary key
	 * @var int $inviteeId
	 **/
	private $inviteeId;
	/**
	 * city for this Invitee
	 * @var string $inviteeCity
	 **/
	private $inviteeCity;
	/**
	 * email for this Invitee
	 * @var string $inviteeEmail
	 **/
	private $inviteeEmail;
	/**
	 * name for this Invitee
	 * @var string $inviteeName
	 **/
	private $inviteeName;
	/**
	 * phone for this Invitee
	 * @var string $inviteePhone
	 **/
	private $inviteePhone;
	/**
	 * state for this Invitee
	 * @var string $inviteeState
	 **/
	private $inviteeState;
	/**
	 * street address 1 for this Invitee
	 * @var string $inviteeStreet1
	 **/
	private $inviteeStreet1;
	/**
	 * street address 2 for this Invitee
	 * @var string $inviteeStreet2
	 **/
	private $inviteeStreet2;
	/**
	 * invite token for this Invitee
	 * @var string $inviteeToken
	 **/
	private $inviteeToken;
	/**
	 * ZIP code for this Invitee
	 * @var string $inviteeZip
	 **/
	private $inviteeZip;

	/**
	 * accessor method for invitee id
	 *
	 * @return int|null value of invitee id
	 **/
	public function getInviteeId() {
		return($this->inviteeId);
	}

	/**
	 * accessor method for invitee city
	 *
	 * @return string value of invitee city
	 **/
	public function getInviteeCity() {
		return($this->inviteeCity);
	}

	/**
	 * accessor method for invitee email
	 *
	 * @return string value of invitee email
	 **/
	public function getInviteeEmail() {
		return($this->inviteeEmail);
	}

	/**
	 * accessor method for invitee city
	 *
	 * @return string value of invitee city
	 **/
	public function getInviteeName() {
		return($this->inviteeName);
	}

	/**
	 * accessor method for invitee phone
	 *
	 * @return string|null value of invitee phone
	 **/
	public function getInviteePhone() {
		return($this->inviteePhone);
	}

	/**
	 * accessor method for invitee state
	 *
	 * @return string value of invitee state
	 **/
	public function getInviteeState() {
		return($this->inviteeState);
	}

	/**
	 * accessor method for invitee address line 1
	 *
	 * @return string value of invitee address line 1
	 **/
	public function getInviteeStreet1() {
		return($this->inviteeStreet1);
	}

	/**
	 * accessor method for invitee address street 2
	 *
	 * @return string|null value of invitee address line 2
	 **/
	public function getInviteeStreet2() {
		return($this->inviteeStreet2);
	}

	/**
	 * accessor method for invitee token
	 *
	 * @return string value of invitee token
	 **/
	public function getInviteeToken() {
		return($this->inviteeToken);
	}

	/**
	 * accessor method for invitee ZIP code
	 *
	 * @return string value of invitee ZIP code
	 **/
	public function getInviteeZip() {
		return($this->inviteeZip);
	}
}
