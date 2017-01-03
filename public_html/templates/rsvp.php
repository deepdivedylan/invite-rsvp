<div *ngIf="invitee === undefined">
	<h1>Invitee Not Found</h1>
	<div class="alert alert-danger" role="alert">
		Invitee not found. Please verify the link and try again.
	</div>
</div>
<div *ngIf="invitee !== undefined && invitee !== null">
	<form #rsvpForm="ngForm" class="form-horizontal well" name="rsvpForm" id="rsvpForm" (ngSubmit)="sendRsvp();" novalidate>
		<h1>RSVP For {{ invitee.inviteeName }}</h1>
		<div class="form-group" [ngClass]="{ 'has-error': rsvpNumPeople.touched && rsvpNumPeople.invalid }">
			<label for="rsvpNumPeople">Number of People</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-users" aria-hidden="true"></i>
				</div>
				<input type="number" id="rsvpNumPeople" name="rsvpNumPeople" class="form-control" required min="0" step="1" [(ngModel)]="rsvp.rsvpNumPeople" #rsvpNumPeople="ngModel" />
			</div>
			<div [hidden]="rsvpNumPeople.valid || rsvpNumPeople.pristine" class="alert alert-danger" role="alert">
				<p *ngIf="rsvpNumPeople.errors?.min">Number of people is required.</p>
				<p *ngIf="rsvpNumPeople.errors?.required">Number of people cannot be negative.</p>
			</div>
			<div *ngIf="rsvp.rsvpNumPeople === 0" class="alert alert-warning" role="alert">
				<i class="fa fa-frown-o" aria-hidden="true"></i> We're so sorry you can't join us. <i class="fa fa-frown-o" aria-hidden="true"></i>
			</div>
			<div *ngIf="rsvp.rsvpNumPeople > 0" class="alert alert-info" role="alert">
				<i class="fa fa-smile-o" aria-hidden="true"></i> You are RSVPing for yourself and {{ rsvp.rsvpNumPeople - 1 }} other people. So glad you can make it! <i class="fa fa-smile-o" aria-hidden="true"></i>
			</div>
		</div>
		<div class="form-group" [ngClass]="{ 'has-error': rsvpComment.touched && rsvpComment.invalid }">
			<label for="rsvpComment">Comment (Optional)</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-commenting" aria-hidden="true"></i>
				</div>
				<input type="text" id="rsvpComment" name="rsvpComment" placeholder="Tell us something&hellip;" class="form-control" maxlength="255" [(ngModel)]="rsvp.rsvpComment" #rsvpComment="ngModel" />
			</div>
			<div [hidden]="rsvpComment.valid || rsvpComment.pristine" class="alert alert-danger" role="alert">
				<p *ngIf="rsvpComment.errors?.maxlength">Comment cannot be more than 255 characters.</p>
			</div>
		</div>
		<button class="btn btn-lg btn-info" type="submit" [disabled]="rsvpForm.invalid"><i class="fa fa-check"></i>&nbsp;RSVP</button>
		<button class="btn btn-lg btn-warning" type="reset"><i class="fa fa-ban"></i>&nbsp;Reset</button>
	</form>
	<div *ngIf="status !== null" class="alert alert-dismissible" [ngClass]="status.type" role="alert">
		<button type="button" class="close" aria-label="Close" (click)="status = null;"><span aria-hidden="true">&times;</span></button>
		{{ status.message }}
	</div>
</div>
