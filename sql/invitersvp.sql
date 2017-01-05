DROP TABLE IF EXISTS rsvp;
DROP TABLE IF EXISTS invitee;

CREATE TABLE invitee (
	inviteeId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	inviteeCity VARCHAR(64) NOT NULL,
	inviteeCountry CHAR(2) NOT NULL,
	inviteeEmail VARCHAR(64),
	inviteeName VARCHAR(64) NOT NULL,
	inviteePhone VARCHAR(24),
	inviteeState VARCHAR(32) NOT NULL,
	inviteeStreet1 VARCHAR(64) NOT NULL,
	inviteeStreet2 VARCHAR(64),
	inviteeToken CHAR(32) NOT NULL,
	inviteeZip VARCHAR(10) NOT NULL,
	PRIMARY KEY(inviteeId)
);

CREATE TABLE rsvp (
	rsvpId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	rsvpInviteeId INT UNSIGNED NOT NULL,
	rsvpBrowser VARCHAR(128) NOT NULL,
	rsvpComment VARCHAR(128),
	rsvpIpAddress VARBINARY(16) NOT NULL,
	rsvpNumPeople TINYINT UNSIGNED NOT NULL,
	rsvpTimestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	UNIQUE(rsvpInviteeId),
	FOREIGN KEY(rsvpInviteeId) REFERENCES invitee(inviteeId),
	PRIMARY KEY(rsvpId)
);
