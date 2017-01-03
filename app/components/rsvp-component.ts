import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {Invitee} from "../classes/invitee";
import {InviteeService} from "../services/invitee-service";
import {Rsvp} from "../classes/rsvp";
import {Status} from "../classes/status";
import "rxjs/add/operator/switchMap";

@Component({
	templateUrl: "./templates/rsvp.php"
})

export class RsvpComponent implements OnInit {
	invitee : Invitee = null;
	rsvp : Rsvp = new Rsvp(null, null, "", "", "", 0, new Date());
	status : Status = null;

	constructor(private inviteeService: InviteeService, private route: ActivatedRoute) {}

	ngOnInit() : void {
		this.getInviteeByToken();
	}

	getInviteeByToken() : void {
		this.route.params
			.switchMap((params : Params) => this.inviteeService.getInviteeByInviteeToken(params["inviteeToken"]))
			.subscribe(invitee => this.invitee = invitee);
	}

}
