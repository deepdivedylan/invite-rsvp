import {Component, OnInit} from "@angular/core";
import {Router} from "@angular/router";
import {Invitee} from "../classes/invitee";
import {InviteeService} from "../services/invitee-service";
import {LoginStatus} from "../classes/login-status";
import {LoginService} from "../services/login-service";
import {Rsvp} from "../classes/rsvp";
import {RsvpService} from "../services/rsvp-service";

@Component({
	templateUrl: "./templates/splash.php"
})

export class SplashComponent implements OnInit {
	private displayDebug : boolean = false;
	private loginStatus : LoginStatus = new LoginStatus(false);
	private invitees : Invitee[] = [];
	private rsvps : Rsvp[] = [];
	private rsvpCount : number = 0;

	constructor(private inviteeService: InviteeService, private loginService: LoginService, private rsvpService: RsvpService, private router: Router) {}

	ngOnInit() : void {
		this.loginService.isLoggedIn()
			.subscribe(loginStatus => {
				this.loginStatus = loginStatus;
				if(this.loginStatus.loginStatus === false) {
					this.router.navigate(["/login"]);
				} else {
					this.getAllInvitees();
					this.getAllRsvps();
				}
			});
	}

	getAllInvitees() : void {
		this.inviteeService.getAllInvitees()
			.subscribe(invitees => this.invitees = invitees);
	}

	getAllRsvps() : void {
		this.rsvpService.getAllRsvps()
			.subscribe(rsvps => {
				this.rsvps = rsvps;
				this.rsvpCount = this.rsvps.reduce((count, rsvp) => count + rsvp.rsvpNumPeople, 0);
			});
	}

	getRsvpInvitee(rsvpInviteeId : number) : Invitee {
		return(this.invitees.filter(invitee => invitee.inviteeId === rsvpInviteeId).shift());
	}

	toggleDebug() : void {
		this.displayDebug = !this.displayDebug;
	}
}
