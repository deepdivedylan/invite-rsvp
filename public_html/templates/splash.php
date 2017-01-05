<h1>Invite &amp; RSVP Admin</h1>
<h2>Invitees</h2>
<div>
	<a class="btn btn-info" (click)="toggleForm();"><i class="fa fa-user-plus"></i> Add Invitee</a>
</div>
<form *ngIf="displayForm" #inviteeForm="ngForm" name="inviteeForm" id="inviteeForm" class="form-horizontal well" (ngSubmit)="createInvitee();" novalidate>
	<div class="form-group" [ngClass]="{ 'has-error': inviteeName.touched && inviteeName.invalid }">
		<label for="inviteeName">Invitee Name</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-user" aria-hidden="true"></i>
			</div>
			<input type="text" id="inviteeName" name="inviteeName" placeholder="Invitee Name" class="form-control" maxlength="64" [(ngModel)]="invitee.inviteeName" #inviteeName="ngModel" required />
		</div>
		<div [hidden]="inviteeName.valid || inviteeName.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="inviteeName.errors?.maxlength">Name cannot be more than 64 characters.</p>
			<p *ngIf="inviteeName.errors?.required">Name is required.</p>
		</div>
	</div>
	<div class="form-group" [ngClass]="{ 'has-error': inviteeEmail.touched && inviteeEmail.invalid }">
		<label for="inviteeEmail">Invitee Email</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-envelope" aria-hidden="true"></i>
			</div>
			<input type="email" id="inviteeEmail" name="inviteeEmail" placeholder="Invitee Email" class="form-control" maxlength="64" [(ngModel)]="invitee.inviteeEmail" #inviteeEmail="ngModel" />
		</div>
		<div [hidden]="inviteeEmail.valid || inviteeEmail.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="inviteeEmail.errors?.email">Email is not a valid Email address.</p>
			<p *ngIf="inviteeEmail.errors?.maxlength">Email cannot be more than 64 characters.</p>
		</div>
	</div>
	<div class="form-group" [ngClass]="{ 'has-error': inviteePhone.touched && inviteePhone.invalid }">
		<label for="inviteePhone">Invitee Phone</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-phone" aria-hidden="true"></i>
			</div>
			<input type="text" id="inviteePhone" name="inviteePhone" placeholder="Invitee Phone" class="form-control" maxlength="24" [(ngModel)]="invitee.inviteePhone" #inviteePhone="ngModel" />
		</div>
		<div [hidden]="inviteePhone.valid || inviteePhone.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="inviteePhone.errors?.maxlength">Phone cannot be more than 24 characters.</p>
		</div>
	</div>
	<div class="form-group" [ngClass]="{ 'has-error': inviteeStreet1.touched && inviteeStreet1.invalid }">
		<label for="inviteeStreet1">Invitee Street</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-road" aria-hidden="true"></i>
			</div>
			<input type="text" id="inviteeStreet1" name="inviteeStreet1" placeholder="Invitee Street" class="form-control" maxlength="64" [(ngModel)]="invitee.inviteeStreet1" #inviteeStreet1="ngModel" required />
		</div>
		<div [hidden]="inviteeStreet1.valid || inviteeStreet1.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="inviteeStreet1.errors?.maxlength">Street cannot be more than 64 characters.</p>
			<p *ngIf="inviteeStreet1.errors?.required">Street is required.</p>
		</div>
	</div>
	<div class="form-group" [ngClass]="{ 'has-error': inviteeStreet2.touched && inviteeStreet2.invalid }">
		<label for="inviteeStreet2">Invitee Street 2</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-road" aria-hidden="true"></i>
			</div>
			<input type="text" id="inviteeStreet2" name="inviteeStreet2" placeholder="Invitee Street 2" class="form-control" maxlength="64" [(ngModel)]="invitee.inviteeStreet2" #inviteeStreet2="ngModel" />
		</div>
		<div [hidden]="inviteeStreet2.valid || inviteeStreet2.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="inviteeStreet2.errors?.maxlength">Street 2 cannot be more than 64 characters.</p>
		</div>
	</div>
	<div class="form-group" [ngClass]="{ 'has-error': inviteeCity.touched && inviteeCity.invalid }">
		<label for="inviteeCity">Invitee City</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-building" aria-hidden="true"></i>
			</div>
			<input type="text" id="inviteeCity" name="inviteeCity" placeholder="Invitee City" class="form-control" maxlength="64" [(ngModel)]="invitee.inviteeCity" #inviteeCity="ngModel" required />
		</div>
		<div [hidden]="inviteeCity.valid || inviteeCity.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="inviteeCity.errors?.maxlength">City cannot be more than 64 characters.</p>
			<p *ngIf="inviteeCity.errors?.required">City is required.</p>
		</div>
	</div>
	<div class="form-group">
		<label>Country</label>
		<label class="radio-inline">
			<input type="radio" name="inviteeCountry" value="DE" [(ngModel)]="invitee.inviteeCountry"> <img class="flag" src="images/DE.svg" alt="" /> Germany
		</label>
		<label class="radio-inline">
			<input type="radio" name="inviteeCountry" value="MX" [(ngModel)]="invitee.inviteeCountry"> <img class="flag" src="images/MX.svg" alt="" /> M&eacute;xico
		</label>
		<label class="radio-inline">
			<input type="radio" name="inviteeCountry" value="US" [(ngModel)]="invitee.inviteeCountry"> <img class="flag" src="images/US.svg" alt="" /> United States
		</label>
	</div>
	<pre>{{ invitee | json }}</pre>
</form>
<table class="table table-bordered table-responsive table-striped">
	<tr>
		<th>Invitee ID</th>
		<th>Invitee Name</th>
		<th>Invitee Email</th>
		<th>Invitee Phone</th>
		<th>Invitee Street</th>
		<th>Invitee Street 2</th>
		<th>Invitee City</th>
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
		<td>{{ invitee.inviteeCity }}</td>
		<td>{{ invitee.inviteeState }}</td>
		<td>{{ invitee.inviteeZip }}</td>
		<td><img class="flag" [src]="'images/' + invitee.inviteeCountry + '.svg'" [alt]="invitee.inviteeCountry" /></td>
	</tr>
</table>
<hr />
<h2>RSVPs</h2>
<p><em>Number of RSVPs:</em> {{ rsvpCount }}</p>
<div class="checkbox">
	<label for="debugCheckbox">
		<input type="checkbox" name="debugCheckbox" id="debugCheckbox" (change)="toggleDebug();">
		Display Debug Information
	</label>
</div>
<table class="table table-bordered table-responsive table-striped">
	<tr>
		<th>RSVP ID</th>
		<th>RSVP Invitee Name</th>
		<th>RSVP Comment</th>
		<th>RSVP Number of People</th>
		<th>RSVP Timestamp</th>
		<th *ngIf="displayDebug">RSVP Browser</th>
		<th *ngIf="displayDebug">RSVP IP Address</th>
	</tr>
	<tr *ngFor="let rsvp of rsvps">
		<td>{{ rsvp.rsvpId }}</td>
		<td>{{ getRsvpInvitee(rsvp.rsvpInviteeId).inviteeName }}</td>
		<td>{{ rsvp.rsvpComment }}</td>
		<td>{{ rsvp.rsvpNumPeople }}</td>
		<td>{{ rsvp.rsvpTimestamp | date : "medium" }}</td>
		<td *ngIf="displayDebug">{{ rsvp.rsvpBrowser }}</td>
		<td *ngIf="displayDebug">{{ rsvp.rsvpIpAddress }}</td>
	</tr>
</table>
