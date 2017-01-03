import {Component, OnInit} from "@angular/core";
import {Router} from "@angular/router";
import {Invitee} from "../classes/invitee";
import {InviteeService} from "../services/invitee-service";

@Component({
	templateUrl: "./templates/rsvp.php"
})

export class RsvpComponent implements OnInit {


	constructor(private inviteeService: InviteeService, private router: Router) {}

	ngOnInit() : void {
		;
	}
}
