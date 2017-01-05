<h1>Invite &amp; RSVP Admin</h1>
<h2>Invitees</h2>
<table class="table table-bordered table-responsive table-striped table-word-wrap">
	<tr>
		<th>Invitee ID</th>
		<th>Invitee Name</th>
		<th>Invitee Email</th>
		<th>Invitee Phone</th>
		<th>Invitee Street</th>
		<th>Invitee Street 2</th>
		<th>Invitee State</th>
		<th>Invitee ZIP</th>
		<th>Invitee Country</th>
	</tr>
	<tr *ngFor="let invitee of invitees">
		<td>{{ invitee.inviteeId }}</td>
		<td>{{ invitee.inviteeName }}</td>
		<td><a [href]="'mailto:' + invitee.inviteeEmail">{{ invitee.inviteeEmail }}</a></td>
		<td><a [href]="'tel:' + invitee.inviteePhone">{{ invitee.inviteePhone }}</a></td>
		<td>{{ invitee.inviteeStreet1 }}</td>
		<td>{{ invitee.inviteeStreet2 }}</td>
		<td>{{ invitee.inviteeState }}</td>
		<td>{{ invitee.inviteeZip }}</td>
		<td>{{ invitee.inviteeCountry }}</td>
	</tr>
</table>
<hr />
<h2>RSVPs</h2>
<table class="table table-bordered table-responsive table-striped table-word-wrap">
	<tr>
		<th>RSVP ID</th>
		<th>RSVP Invitee ID</th>
		<th>RSVP Comment</th>
		<th>RSVP Number of People</th>
		<th>RSVP Timestamp</th>
		<th>RSVP Browser</th>
		<th>RSVP IP Address</th>
	</tr>
	<tr *ngFor="let rsvp of rsvps">
		<td>{{ rsvp.rsvpId }}</td>
		<td>{{ rsvp.rsvpInviteeId }}</td>
		<td>{{ rsvp.rsvpComment }}</td>
		<td>{{ rsvp.rsvpNumPeople }}</td>
		<td>{{ rsvp.rsvpTimestamp }}</td>
		<td>{{ rsvp.rsvpBrowser }}</td>
		<td>{{ rsvp.rsvpIpAddress }}</td>
	</tr>
</table>
