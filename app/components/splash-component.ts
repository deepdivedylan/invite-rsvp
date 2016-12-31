import {Component, OnInit} from "@angular/core";
import {Router} from "@angular/router";
import {LoginStatus} from "../classes/login-status";
import {LoginService} from "../services/login-service";

@Component({
	templateUrl: "./templates/splash.php"
})

export class SplashComponent implements OnInit {
	private loginStatus : LoginStatus = new LoginStatus(false);

	constructor(private loginService: LoginService, private router: Router) {}

	ngOnInit() : void {
		this.loginService.isLoggedIn()
			.subscribe(loginStatus => {
				this.loginStatus = loginStatus;
				if(this.loginStatus.loginStatus === false) {
					this.router.navigate(["/login"]);
				}
			});
	}
}
