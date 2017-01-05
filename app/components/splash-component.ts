import {Component, OnInit, ViewChild} from "@angular/core";
import {Router} from "@angular/router";
import {Invitee} from "../classes/invitee";
import {InviteeService} from "../services/invitee-service";
import {LoginStatus} from "../classes/login-status";
import {LoginService} from "../services/login-service";
import {Rsvp} from "../classes/rsvp";
import {RsvpService} from "../services/rsvp-service";
import {State} from "../classes/state";
import {Status} from "../classes/status";

@Component({
	templateUrl: "./templates/splash.php"
})

export class SplashComponent implements OnInit {
	@ViewChild("inviteeForm")
	private inviteeForm : any = null;
	private displayDebug : boolean = false;
	private displayForm : boolean = false;
	private loginStatus : LoginStatus = new LoginStatus(false);
	private invitee : Invitee = new Invitee(null, null, null, null, null, null, null, null, null, null);
	private invitees : Invitee[] = [];
	private rsvps : Rsvp[] = [];
	private rsvpCount : number = 0;
	private status : Status = null;

	// minified array of states, messy but saves an API call
	private states : State[] = [{"stateAbbreviation":"AL","stateName":"Alabama"},{"stateAbbreviation":"AK","stateName":"Alaska"},{"stateAbbreviation":"AS","stateName":"American Samoa"},{"stateAbbreviation":"AZ","stateName":"Arizona"},{"stateAbbreviation":"AR","stateName":"Arkansas"},{"stateAbbreviation":"CA","stateName":"California"},{"stateAbbreviation":"CO","stateName":"Colorado"},{"stateAbbreviation":"CT","stateName":"Connecticut"},{"stateAbbreviation":"DE","stateName":"Delaware"},{"stateAbbreviation":"DC","stateName":"District Of Columbia"},{"stateAbbreviation":"FM","stateName":"Federated States Of Micronesia"},{"stateAbbreviation":"FL","stateName":"Florida"},{"stateAbbreviation":"GA","stateName":"Georgia"},{"stateAbbreviation":"GU","stateName":"Guam"},{"stateAbbreviation":"HI","stateName":"Hawaii"},{"stateAbbreviation":"ID","stateName":"Idaho"},{"stateAbbreviation":"IL","stateName":"Illinois"},{"stateAbbreviation":"IN","stateName":"Indiana"},{"stateAbbreviation":"IA","stateName":"Iowa"},{"stateAbbreviation":"KS","stateName":"Kansas"},{"stateAbbreviation":"KY","stateName":"Kentucky"},{"stateAbbreviation":"LA","stateName":"Louisiana"},{"stateAbbreviation":"ME","stateName":"Maine"},{"stateAbbreviation":"MH","stateName":"Marshall Islands"},{"stateAbbreviation":"MD","stateName":"Maryland"},{"stateAbbreviation":"MA","stateName":"Massachusetts"},{"stateAbbreviation":"MI","stateName":"Michigan"},{"stateAbbreviation":"MN","stateName":"Minnesota"},{"stateAbbreviation":"MS","stateName":"Mississippi"},{"stateAbbreviation":"MO","stateName":"Missouri"},{"stateAbbreviation":"MT","stateName":"Montana"},{"stateAbbreviation":"NE","stateName":"Nebraska"},{"stateAbbreviation":"NV","stateName":"Nevada"},{"stateAbbreviation":"NH","stateName":"New Hampshire"},{"stateAbbreviation":"NJ","stateName":"New Jersey"},{"stateAbbreviation":"NM","stateName":"New Mexico"},{"stateAbbreviation":"NY","stateName":"New York"},{"stateAbbreviation":"NC","stateName":"North Carolina"},{"stateAbbreviation":"ND","stateName":"North Dakota"},{"stateAbbreviation":"MP","stateName":"Northern Mariana Islands"},{"stateAbbreviation":"OH","stateName":"Ohio"},{"stateAbbreviation":"OK","stateName":"Oklahoma"},{"stateAbbreviation":"OR","stateName":"Oregon"},{"stateAbbreviation":"PW","stateName":"Palau"},{"stateAbbreviation":"PA","stateName":"Pennsylvania"},{"stateAbbreviation":"PR","stateName":"Puerto Rico"},{"stateAbbreviation":"RI","stateName":"Rhode Island"},{"stateAbbreviation":"SC","stateName":"South Carolina"},{"stateAbbreviation":"SD","stateName":"South Dakota"},{"stateAbbreviation":"TN","stateName":"Tennessee"},{"stateAbbreviation":"TX","stateName":"Texas"},{"stateAbbreviation":"UT","stateName":"Utah"},{"stateAbbreviation":"VT","stateName":"Vermont"},{"stateAbbreviation":"VI","stateName":"Virgin Islands"},{"stateAbbreviation":"VA","stateName":"Virginia"},{"stateAbbreviation":"WA","stateName":"Washington"},{"stateAbbreviation":"WV","stateName":"West Virginia"},{"stateAbbreviation":"WI","stateName":"Wisconsin"},{"stateAbbreviation":"WY","stateName":"Wyoming"}];

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

	createInvitee() : void {
		this.inviteeService.createInvitee(this.invitee)
			.subscribe(status => {
				this.status = status;
				if(this.status.status === 200) {
					this.getAllInvitees();
					this.inviteeForm.reset();
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

	toggleForm() : void {
		this.displayForm = !this.displayForm;
	}
}
