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
	 * constructor for this invitee
	 *
	 * @param int|null $newInviteeId id of this invitee or null if a new invitee
	 * @param string $newInviteeCity city of this invitee
	 * @param string|null $newInviteeEmail email of this invitee (nullable)
	 * @param string $newInviteeName name of this invitee
	 * @param string|null $newInviteePhone phone of this invitee (nullable)
	 * @param string $newInviteeState state of this invitee
	 * @param string $newInviteeStreet1 address line 1 of this invitee
	 * @param string $newInviteeStreet2 address line 2 of this invitee (nullable)
	 * @param string $newInviteeToken token of this invitee
	 * @param string $newInviteeZip ZIP code of this invitee
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newInviteeId = null, string $newInviteeCity, string $newInviteeEmail = null, string $newInviteeName, string $newInviteePhone = null, string $newInviteeState, string $newInviteeStreet1, string $newInviteeStreet2 = null, string $newInviteeToken, string $newInviteeZip) {
		try {
			$this->setInviteeId($newInviteeId);
			$this->setInviteeCity($newInviteeCity);
			$this->setInviteeEmail($newInviteeEmail);
			$this->setInviteeName($newInviteeName);
			$this->setInviteePhone($newInviteePhone);
			$this->setInviteeState($newInviteeState);
			$this->setInviteeStreet1($newInviteeStreet1);
			$this->setInviteeStreet2($newInviteeStreet2);
			$this->setInviteeToken($newInviteeToken);
			$this->setInviteeZip($newInviteeZip);
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
	 * mutator method for invitee id
	 *
	 * @param int|null $newInviteeId new value of invitee id
	 * @throws \RangeException if $newInviteeId is not positive
	 * @throws \TypeError if $newInviteeId is not an integer
	 **/
	public function setInviteeId(int $newInviteeId = null) {
		// base case: if the invitee id is null, this a new invitee without a mySQL assigned id (yet)
		if($newInviteeId === null) {
			$this->inviteeId = null;
			return;
		}

		// verify the invitee id is positive
		if($newInviteeId <= 0) {
			throw(new \RangeException("invitee id is not positive"));
		}

		// convert and store the invitee id
		$this->inviteeId = $newInviteeId;
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
	 * mutator method for invitee city
	 *
	 * @param string $newInviteeCity new value of invitee city
	 * @throws \InvalidArgumentException if $newInviteeCity is not a string or insecure
	 * @throws \RangeException if $newInviteeCity is > 64 characters
	 * @throws \TypeError if $newInviteeCity is not a string
	 **/
	public function setInviteeCity(string $newInviteeCity) {
		// verify the invitee city is secure
		$newInviteeCity = trim($newInviteeCity);
		$newInviteeCity = filter_var($newInviteeCity, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newInviteeCity) === true) {
			throw(new \InvalidArgumentException("invitee city is empty or insecure"));
		}

		// verify the invitee city will fit in the database
		if(strlen($newInviteeCity) > 64) {
			throw(new \RangeException("invitee city too large"));
		}

		// store the invitee city
		$this->inviteeCity = $newInviteeCity;
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
	 * mutator method for invitee email
	 *
	 * @param string|null $newInviteeEmail new value of invitee email
	 * @throws \InvalidArgumentException if $newInviteeEmail is not a string or insecure
	 * @throws \RangeException if $newInviteeEmail is > 64 characters
	 * @throws \TypeError if $newInviteeEmail is not a string
	 **/
	public function setInviteeEmail(string $newInviteeEmail = null) {
		// base case: if the invitee email is null, allow it to be null
		if($newInviteeEmail === null) {
			$this->inviteeEmail = null;
			return;
		}

		// verify the invitee email is secure
		$newInviteeEmail = trim($newInviteeEmail);
		$newInviteeEmail = filter_var($newInviteeEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newInviteeEmail) === true) {
			throw(new \InvalidArgumentException("invitee email is empty or insecure"));
		}

		// verify the invitee email will fit in the database
		if(strlen($newInviteeEmail) > 64) {
			throw(new \RangeException("invitee email too large"));
		}

		// store the invitee email
		$this->inviteeEmail = $newInviteeEmail;
	}

	/**
	 * accessor method for invitee name
	 *
	 * @return string value of invitee name
	 **/
	public function getInviteeName() {
		return($this->inviteeName);
	}

	/**
	 * mutator method for invitee name
	 *
	 * @param string $newInviteeName new value of invitee name
	 * @throws \InvalidArgumentException if $newInviteeName is not a string or insecure
	 * @throws \RangeException if $newInviteeName is > 64 characters
	 * @throws \TypeError if $newInviteeName is not a string
	 **/
	public function setInviteeName(string $newInviteeName) {
		// verify the invitee name is secure
		$newInviteeName = trim($newInviteeName);
		$newInviteeName = filter_var($newInviteeName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newInviteeName) === true) {
			throw(new \InvalidArgumentException("invitee name is empty or insecure"));
		}

		// verify the invitee name will fit in the database
		if(strlen($newInviteeName) > 64) {
			throw(new \RangeException("invitee name too large"));
		}

		// store the invitee name
		$this->inviteeName = $newInviteeName;
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
	 * mutator method for invitee phone
	 *
	 * @param string|null $newInviteePhone new value of invitee phone
	 * @throws \InvalidArgumentException if $newInviteePhone is not a string or insecure
	 * @throws \RangeException if $newInviteePhone is > 24 characters
	 * @throws \TypeError if $newInviteePhone is not a string
	 **/
	public function setInviteePhone(string $newInviteePhone = null) {
		// base case: if the invitee phone is null, allow it to be null
		if($newInviteePhone === null) {
			$this->inviteePhone = null;
			return;
		}

		// verify the invitee phone is secure
		$newInviteePhone = trim($newInviteePhone);
		$newInviteePhone = filter_var($newInviteePhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newInviteePhone) === true) {
			throw(new \InvalidArgumentException("invitee phone is empty or insecure"));
		}

		// verify the invitee phone will fit in the database
		if(strlen($newInviteePhone) > 24) {
			throw(new \RangeException("invitee phone too large"));
		}

		// store the invitee phone
		$this->inviteePhone = $newInviteePhone;
	}

	/**
	 * accessor method for invitee state
	 *
	 * @return string value of invitee state
	 **/
	public function getInviteeState() {
		return($this->inviteeState);
	}

	public function setInviteeState(string $newInviteeState) {
		// verify the invitee state actually exists
		$states = ["AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FM", "FL", "GA", "GU", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MH", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "MP", "OH", "OK", "OR", "PW", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VI", "VA", "WA", "WV", "WI", "WY"];
		if(in_array($newInviteeState, $states) === false) {
			throw(new \InvalidArgumentException("invitee state is invalid"));
		}

		// store the invitee state
		$this->inviteeState = $newInviteeState;
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
	 * mutator method for invitee address line 1
	 *
	 * @param string $newInviteeStreet1 new value of invitee address line 1
	 * @throws \InvalidArgumentException if $newInviteeStreet1 is not a string or insecure
	 * @throws \RangeException if $newInviteeStreet1 is > 64 characters
	 * @throws \TypeError if $newInviteeStreet1 is not a string
	 **/
	public function setInviteeStreet1(string $newInviteeStreet1) {
		// verify the invitee address line 1 is secure
		$newInviteeStreet1 = trim($newInviteeStreet1);
		$newInviteeStreet1 = filter_var($newInviteeStreet1, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newInviteeStreet1) === true) {
			throw(new \InvalidArgumentException("invitee address line 1 is empty or insecure"));
		}

		// verify the invitee address line 1 will fit in the database
		if(strlen($newInviteeStreet1) > 64) {
			throw(new \RangeException("invitee address line 1 too large"));
		}

		// store the invitee address line 1
		$this->inviteeStreet1 = $newInviteeStreet1;
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
	 * mutator method for invitee address line 2
	 *
	 * @param string|null $newInviteeStreet2 new value of invitee address line 2
	 * @throws \InvalidArgumentException if $newInviteeStreet2 is not a string or insecure
	 * @throws \RangeException if $newInviteeStreet2 is > 64 characters
	 * @throws \TypeError if $newInviteeStreet2 is not a string
	 **/
	public function setInviteeStreet2(string $newInviteeStreet2 = null) {
		// base case: if the invitee address line 2 is null, allow it to be null
		if($newInviteeStreet2 === null) {
			$this->inviteeStreet2 = null;
			return;
		}

		// verify the invitee address line 2 is secure
		$newInviteeStreet2 = trim($newInviteeStreet2);
		$newInviteeStreet2 = filter_var($newInviteeStreet2, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newInviteeStreet2) === true) {
			throw(new \InvalidArgumentException("invitee address line 2 is empty or insecure"));
		}

		// verify the invitee address line 2 will fit in the database
		if(strlen($newInviteeStreet2) > 64) {
			throw(new \RangeException("invitee address line 2 too large"));
		}

		// store the invitee address line 2
		$this->inviteeStreet2 = $newInviteeStreet2;
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
	 * mutator method for invitee token
	 *
	 * @param string|null $newInviteeToken new value of invitee token
	 * @throws \InvalidArgumentException if $newInviteeToken is not a string of hexits
	 * @throws \RangeException if $newInviteeToken is not 32 characters
	 * @throws \TypeError if $newInviteeToken is not a string
	 **/
	public function setInviteeToken(string $newInviteeToken) {
		// verify the invitee token is only hexits
		$newInviteeToken = trim(strtolower($newInviteeToken));
		if(ctype_xdigit($newInviteeToken) === false) {
			throw(new \InvalidArgumentException("invitee token is invalid"));
		}

		// verify the invitee token is 32 characters long
		if(strlen($newInviteeToken) !== 32) {
			throw(new \RangeException("invitee token is invalid"));
		}

		// store the invitee token
		$this->inviteeToken = $newInviteeToken;
	}

	/**
	 * accessor method for invitee ZIP code
	 *
	 * @return string value of invitee ZIP code
	 **/
	public function getInviteeZip() {
		return($this->inviteeZip);
	}

	/**
	 * mutator method for invitee ZIP code
	 *
	 * @param string|null $newInviteeZip new value of invitee ZIP code
	 * @throws \InvalidArgumentException if $newInviteeZip is not syntactically valid
	 * @throws \TypeError if $newInviteeZip is not a string
	 **/
	public function setInviteeZip(string $newInviteeZip) {
		// verify the ZIP code is syntactically valid
		$zipRegex = "/^\d{5}(?:[-\s]\d{4})?$/";
		$newInviteeZip = trim($newInviteeZip);
		$newInviteeZip = filter_var($newInviteeZip, FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => $zipRegex]]);
		if($newInviteeZip === false) {
			throw(new \InvalidArgumentException("invitee ZIP code is invalid"));
		}

		// store the ZIP code
		$this->inviteeZip = $newInviteeZip;
	}

	/**
	 * inserts this Invitee into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		// enforce the inviteeId is null (i.e., don't insert a invitee that already exists)
		if($this->inviteeId !== null) {
			throw(new \PDOException("not a new invitee"));
		}

		// create query template
		$query = "INSERT INTO invitee(inviteeCity, inviteeEmail, inviteeName, inviteePhone, inviteeState, inviteeStreet1, inviteeStreet2, inviteeToken, inviteeZip) VALUES(:inviteeCity, :inviteeEmail, :inviteeName, :inviteePhone, :inviteeState, :inviteeStreet1, :inviteeStreet2, :inviteeToken, :inviteeZip)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["inviteeCity" => $this->inviteeCity, "inviteeEmail" => $this->inviteeEmail, "inviteeName" => $this->inviteeName, "inviteePhone" => $this->inviteePhone, "inviteeState" => $this->inviteeState, "inviteeStreet1" => $this->inviteeStreet1, "inviteeStreet2" => $this->inviteeStreet2, "inviteeToken" => $this->inviteeToken, "inviteeZip" => $this->inviteeZip];
		$statement->execute($parameters);

		// update the null inviteeId with what mySQL just gave us
		$this->inviteeId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes this Invitee from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// enforce the inviteeId is null (i.e., don't delete a invitee that hasn't been inserted)
		if($this->inviteeId === null) {
			throw(new \PDOException("unable to delete a invitee that does not exist"));
		}

		// create query template
		$query = "DELETE FROM invitee WHERE inviteeId = :inviteeId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["inviteeId" => $this->inviteeId];
		$statement->execute($parameters);
	}

	/**
	 * updates this Invitee in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		// enforce the inviteeId is null (i.e., don't update a invitee that hasn't been inserted)
		if($this->inviteeId === null) {
			throw(new \PDOException("unable to update a invitee that does not exist"));
		}

		// create query template
		$query = "UPDATE invitee SET inviteeCity = :inviteeCity, inviteeEmail = :inviteeEmail, inviteeName = :inviteeName, inviteePhone = :inviteePhone, inviteeState = :inviteeState, inviteeStreet1 = :inviteeStreet1, inviteeStreet2 = :inviteeStreet2, inviteeToken = :inviteeToken, inviteeZip = :inviteeZip WHERE inviteeId = :inviteeId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["inviteeId" => $this->inviteeId, "inviteeCity" => $this->inviteeCity, "inviteeEmail" => $this->inviteeEmail, "inviteeName" => $this->inviteeName, "inviteePhone" => $this->inviteePhone, "inviteeState" => $this->inviteeState, "inviteeStreet1" => $this->inviteeStreet1, "inviteeStreet2" => $this->inviteeStreet2, "inviteeToken" => $this->inviteeToken, "inviteeZip" => $this->inviteeZip];
		$statement->execute($parameters);
	}

	/**
	 * gets the Invitee by inviteeId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $inviteeId invitee id to search for
	 * @return Invitee|null Invitee found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getInviteeByInviteeId(\PDO $pdo, int $inviteeId) {
		// sanitize the $inviteeId before searching
		if($inviteeId <= 0) {
			throw(new \PDOException("invitee id is not positive"));
		}

		// create query template
		$query = "SELECT inviteeId, inviteeCity, inviteeEmail, inviteeName, inviteePhone, inviteeState, inviteeStreet1, inviteeStreet2, inviteeToken, inviteeZip FROM invitee WHERE inviteeId = :inviteeId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["inviteeId" => $inviteeId];
		$statement->execute($parameters);

		// grab the invitee from mySQL
		try {
			$invitee = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$invitee = new Invitee($row["inviteeId"], $row["inviteeCity"], $row["inviteeEmail"], $row["inviteeName"], $row["inviteePhone"], $row["inviteeState"], $row["inviteeStreet1"], $row["inviteeStreet2"], $row["inviteeToken"], $row["inviteeZip"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($invitee);
	}

	/**
	 * gets the Invitee by inviteeToken
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $inviteeToken invitee token to search for
	 * @return Invitee|null Invitee found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getInviteeByInviteeToken(\PDO $pdo, int $inviteeToken) {
		// sanitize the $inviteeId before searching
		if($inviteeId <= 0) {
			throw(new \PDOException("invitee id is not positive"));
		}

		// create query template
		$query = "SELECT inviteeId, inviteeCity, inviteeEmail, inviteeName, inviteePhone, inviteeState, inviteeStreet1, inviteeStreet2, inviteeToken, inviteeZip FROM invitee WHERE inviteeToken = :inviteeToken";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["inviteeToken" => $inviteeToken];
		$statement->execute($parameters);

		// grab the invitee from mySQL
		try {
			$invitee = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$invitee = new Invitee($row["inviteeId"], $row["inviteeCity"], $row["inviteeEmail"], $row["inviteeName"], $row["inviteePhone"], $row["inviteeState"], $row["inviteeStreet1"], $row["inviteeStreet2"], $row["inviteeToken"], $row["inviteeZip"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($invitee);
	}

	/**
	 * gets all Invitees
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Invitees found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllInvitees(\PDO $pdo) {
		// create query template
		$query = "SELECT inviteeId, inviteeCity, inviteeEmail, inviteeName, inviteePhone, inviteeState, inviteeStreet1, inviteeStreet2, inviteeToken, inviteeZip FROM invitee";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of invitees
		$invitees = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$invitee = new Invitee($row["inviteeId"], $row["inviteeCity"], $row["inviteeEmail"], $row["inviteeName"], $row["inviteePhone"], $row["inviteeState"], $row["inviteeStreet1"], $row["inviteeStreet2"], $row["inviteeToken"], $row["inviteeZip"]);
				$invitees[$invitees->key()] = $invitee;
				$invitees->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($invitees);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}
