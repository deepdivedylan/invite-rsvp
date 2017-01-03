<div *ngIf="invitee === undefined">
	<h1>Invitee Not Found</h1>
	<div class="alert alert-danger" role="alert">
		Invitee not found. Please verify the link and try again.
	</div>
</div>
<div *ngIf="invitee !== undefined && invitee !== null">
	<h1>RSVP For {{ invitee.inviteeName }}</h1>
	<div *ngIf="status !== null" class="alert alert-dismissible" [ngClass]="status.type" role="alert">
		<button type="button" class="close" aria-label="Close" (click)="status = null;"><span aria-hidden="true">&times;</span></button>
		{{ status.message }}
	</div>
</div>
