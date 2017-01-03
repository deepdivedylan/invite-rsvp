import {Component, OnInit} from "@angular/core";
import {Router} from "@angular/router";
import {Invitee} from "../classes/invitee";
import {InviteeService} from "../services/invitee-service";
import {LoginStatus} from "../classes/login-status";
import {LoginService} from "../services/login-service";

@Component({
	templateUrl: "./templates/splash.php"
})

export class SplashComponent implements OnInit {
	private loginStatus : LoginStatus = new LoginStatus(false);
	private invitees : Invitee[] = [];

	constructor(private inviteeService: InviteeService, private loginService: LoginService, private router: Router) {}

	ngOnInit() : void {
		this.loginService.isLoggedIn()
			.subscribe(loginStatus => {
				this.loginStatus = loginStatus;
				if(this.loginStatus.loginStatus === false) {
					this.router.navigate(["/login"]);
				} else {
					this.getAllInvitees();
				}
			});
	}

	getAllInvitees() : void {
		this.inviteeService.getAllInvitees()
			.subscribe(invitees => this.invitees = invitees);
	}
}
