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
	 * browser for this Rsvp
	 * @var string $rsvpBrowser
	 **/
	private $rsvpBrowser;
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
	 * constructor for this rsvp
	 *
	 * @param int|null $newRsvpId id of this rsvp or null if a new rsvp
	 * @param string $newRsvpInviteeId invitee id of this rsvp
	 * @param string|null $newRsvpBrowser browser of this rsvp
	 * @param string $newRsvpComment comment for this rsvp
	 * @param string|null $newRsvpIpAddress IP address of this rsvp
	 * @param string $newRsvpNumPeople number of people for this rsvp
	 * @param string $newRsvpTimestamp timestamp of this rsvp
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newRsvpId = null, int $newRsvpInviteeId, string $newRsvpBrowser, string $newRsvpComment = null, string $newRsvpIpAddress, int $newRsvpNumPeople, $newRsvpTimestamp) {
		try {
			$this->setRsvpId($newRsvpId);
			$this->setRsvpInviteeId($newRsvpInviteeId);
			$this->setRsvpBrowser($newRsvpBrowser);
			$this->setRsvpComment($newRsvpComment);
			$this->setRsvpIpAddress($newRsvpIpAddress);
			$this->setRsvpNumPeople($newRsvpNumPeople);
			$this->setRsvpTimestamp($newRsvpTimestamp);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for rsvp id
	 *
	 * @return int|null value of rsvp id
	 **/
	public function getRsvpId() {
		return($this->rsvpId);
	}

	/**
	 * mutator method for rsvp id
	 *
	 * @param int|null $newRsvpId new value of rsvp id
	 * @throws \RangeException if $newRsvpId is not positive
	 * @throws \TypeError if $newRsvpId is not an integer
	 **/
	public function setRsvpId(int $newRsvpId = null) {
		// base case: if the rsvp id is null, this a new rsvp without a mySQL assigned id (yet)
		if($newRsvpId === null) {
			$this->rsvpId = null;
			return;
		}

		// verify the rsvp id is positive
		if($newRsvpId <= 0) {
			throw(new \RangeException("rsvp id is not positive"));
		}

		// convert and store the rsvp id
		$this->rsvpId = $newRsvpId;
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
	 * mutator method for rsvp invitee id
	 *
	 * @param int $newRsvpInviteeId new value of rsvp invitee id
	 * @throws \RangeException if $newRsvpInviteeId is not positive
	 * @throws \TypeError if $newRsvpInviteeId is not an integer
	 **/
	public function setRsvpInviteeId(int $newRsvpInviteeId) {
		// verify the rsvp invitee id is positive
		if($newRsvpInviteeId <= 0) {
			throw(new \RangeException("rsvp invitee id is not positive"));
		}

		// convert and store the rsvp id
		$this->rsvpInviteeId = $newRsvpInviteeId;
	}

	/**
	 * accessor method for rsvp browser
	 *
	 * @return string|null value of rsvp browser
	 **/
	public function getRsvpBrowser() {
		return($this->rsvpBrowser);
	}

	/**
	 * mutator method for rsvp browser
	 *
	 * @param string $newRsvpBrowser new value of rsvp browser
	 * @throws \InvalidArgumentException if $newRsvpBrowser is not a string or insecure
	 * @throws \RangeException if $newRsvpBrowser is > 128 characters
	 * @throws \TypeError if $newRsvpBrowser is not a string
	 **/
	public function setRsvpBrowser(string $newRsvpBrowser) {
		// verify the rsvp browser is secure
		$newRsvpBrowser = trim($newRsvpBrowser);
		$newRsvpBrowser = filter_var($newRsvpBrowser, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newRsvpBrowser) === true) {
			throw(new \InvalidArgumentException("rsvp browser is empty or insecure"));
		}

		// verify the rsvp browser will fit in the database
		if(strlen($newRsvpBrowser) > 128) {
			throw(new \RangeException("rsvp browser too large"));
		}

		// store the rsvp browser
		$this->rsvpBrowser = $newRsvpBrowser;
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
	 * mutator method for rsvp comment
	 *
	 * @param string|null $newRsvpComment new value of rsvp comment
	 * @throws \InvalidArgumentException if $newRsvpComment is not a string or insecure
	 * @throws \RangeException if $newRsvpComment is > 128 characters
	 * @throws \TypeError if $newRsvpComment is not a string
	 **/
	public function setRsvpComment(string $newRsvpComment = null) {
		// base case: if the rsvp comment is null, allow it to be null
		if($newRsvpComment === null) {
			$this->rsvpComment = null;
			return;
		}

		// verify the rsvp comment is secure
		$newRsvpComment = trim($newRsvpComment);
		$newRsvpComment = filter_var($newRsvpComment, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newRsvpComment) === true) {
			throw(new \InvalidArgumentException("rsvp comment is empty or insecure"));
		}

		// verify the rsvp comment will fit in the database
		if(strlen($newRsvpComment) > 128) {
			throw(new \RangeException("rsvp comment too large"));
		}

		// store the rsvp comment
		$this->rsvpComment = $newRsvpComment;
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
	 * Mutator for rsvp IP address
	 *
	 * @param string $newRsvpIpAddress new value of rsvp IP address
	 * @throws \InvalidArgumentException if $newRsvpIpAddress is not a valid IP address
	 **/
	public function setRsvpIpAddress(string $newRsvpIpAddress) {
		// detect the IP's format and assign it in binary mode
		if(@inet_pton($newRsvpIpAddress) !== false) {
			$this->rsvpIpAddress = inet_pton($newRsvpIpAddress);
		} else if(@inet_ntop($ipAddress) !== false) {
			$this->rsvpIpAddress = $newRsvpIpAddress;
		} else {
			throw(new InvalidArgumentException("invalid rsvp IP address"));
		}
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
	 * mutator method for rsvp number of people
	 *
	 * @param int $newRsvpNumPeople new value of rsvp number of people
	 * @throws \RangeException if $newRsvpNumPeople is negative
	 * @throws \TypeError if $newRsvpNumPeople is not an integer
	 **/
	public function setRsvpNumPeople(int $newRsvpNumPeople) {
		// verify the number of people is not negative
		if($newRsvpNumPeople < 0) {
			throw(new \RangeException("rsvp number of people is cannot be negative"));
		}

		// convert and store the number of people
		$this->rsvpNumPeople = $newRsvpNumPeople;
	}

	/**
	 * accessor method for rsvp timestamp
	 *
	 * @return \DateTime value of rsvp timestamp
	 **/
	public function getRsvpTimestamp() {
		return($this->rsvpTimestamp);
	}

	/**
	 * mutator method for rsvp timestamp
	 *
	 * @param \DateTime|string|null $newRsvpTimestamp rsvp timestamp as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newRsvpTimestamp is not a valid object or string
	 * @throws \RangeException if $newRsvpTimestamp is a date that does not exist
	 **/
	public function setRsvpTimestamp($newRsvpTimestamp = null) {
		// base case: if the rsvp timestamp is null, use the current date and time
		if($newRsvpTimestamp === null) {
			$this->rsvpTimestamp = new \DateTime();
			return;
		}

		// store the rsvp timestamp
		try {
			$newRsvpTimestamp = self::validateDateTime($newRsvpTimestamp);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->rsvpTimestamp = $newRsvpTimestamp;
	}

	/**
	 * inserts this Rsvp into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// enforce the rsvpId is null (i.e., don't insert a rsvp that already exists)
		if($this->rsvpId !== null) {
			throw(new \PDOException("not a new rsvp"));
		}

		// create query template
		$query = "INSERT INTO rsvp(rsvpInviteeId, rsvpBrowser, rsvpComment, rsvpIpAddress, rsvpNumPeople) VALUES(:rsvpInviteeId, :rsvpBrowser, :rsvpComment, :rsvpIpAddress, :rsvpNumPeople)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["rsvpInviteeId" => $this->rsvpInviteeId, "rsvpBrowser" => $this->rsvpBrowser, "rsvpComment" => $this->rsvpComment, "rsvpIpAddress" => $this->rsvpIpAddress, "rsvpNumPeople" => $this->rsvpNumPeople];
		$statement->execute($parameters);

		// update the null rsvpId with what mySQL just gave us
		$this->rsvpId = intval($pdo->lastInsertId());

		// estimate the timestamp by using the current time (not perfect, but saves another SQL query)
		$this->rsvpTimestamp = new \DateTime();
	}

	/**
	 * deletes this Rsvp from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// enforce the rsvpId is null (i.e., don't delete a rsvp that hasn't been inserted)
		if($this->inviteeId === null) {
			throw(new \PDOException("unable to delete a rsvp that does not exist"));
		}

		// create query template
		$query = "DELETE FROM rsvp WHERE rsvpId = :rsvpId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["rsvpId" => $this->rsvpId];
		$statement->execute($parameters);
	}

	/**
	 * updates this Rsvp in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		// enforce the rsvpId is null (i.e., don't update a rsvp that hasn't been inserted)
		if($this->rsvpId === null) {
			throw(new \PDOException("unable to update a rsvpId that does not exist"));
		}

		// create query template
		$query = "UPDATE rsvp SET rsvpInviteeId = :rsvpInviteeId, rsvpBrowser = :rsvpBrowser, rsvpComment = :rsvpComment, rsvpIpAddress = :rsvpIpAddress, rsvpNumPeople = :rsvpNumPeople WHERE rsvpId = :rsvpId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["rsvpInviteeId" => $this->rsvpInviteeId, "rsvpBrowser" => $this->rsvpBrowser, "rsvpComment" => $this->rsvpComment, "rsvpIpAddress" => $this->rsvpIpAddress, "rsvpNumPeople" => $this->rsvpNumPeople, "rsvpId" => $this->rsvpId];
		$statement->execute($parameters);
	}

	/**
	 * gets the Rsvp by rsvpId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $rsvpId rsvp id to search for
	 * @return Invitee|null Rsvp found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getRsvpByRsvpId(\PDO $pdo, int $rsvpId) {
		// sanitize the rsvpId before searching
		if($rsvpId <= 0) {
			throw(new \PDOException("rsvp id is not positive"));
		}

		// create query template
		$query = "SELECT rsvpId, rsvpInviteeId, rsvpBrowser, rsvpComment, rsvpIpAddress, rsvpNumPeople, rsvpTimestamp FROM rsvp WHERE rsvpId = :rsvpId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["rsvpId" => $rsvpId];
		$statement->execute($parameters);

		// grab the rsvp from mySQL
		try {
			$rsvp = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$rsvp = new Rsvp($row["rsvpId"], $row["rsvpInviteeId"], $row["rsvpBrowser"], $row["rsvpComment"], $row["rsvpIpAddress"], $row["rsvpNumPeople"], $row["rsvpTimestamp"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($invitee);
	}

	/**
	 * gets all Rsvps
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Rsvps found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllInvitees(\PDO $pdo) {
		// create query template
		$query = "SELECT rsvpId, rsvpInviteeId, rsvpBrowser, rsvpComment, rsvpIpAddress, rsvpNumPeople, rsvpTimestamp FROM rsvp";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of invitees
		$rsvps = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$rsvp = new Rsvp($row["rsvpId"], $row["rsvpInviteeId"], $row["rsvpBrowser"], $row["rsvpComment"], $row["rsvpIpAddress"], $row["rsvpNumPeople"], $row["rsvpTimestamp"]);
				$rsvps[$rsvps->key()] = $rsvp;
				$rsvps->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($rsvps);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["rsvpIpAddress"] = inet_ntop($this->rsvpIpAddress);
		$fields["rsvpTimestamp"] = $this->rsvpTimestamp->getTimestamp() * 1000;
		return($fields);
	}
}
