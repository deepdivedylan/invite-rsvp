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
}
