import {Component, ViewChild} from "@angular/core";
import {Router} from "@angular/router";
import {Login} from "../classes/login";
import {Status} from "../classes/status";
import {LoginService} from "../services/login-service";

@Component({
	templateUrl: "./templates/login.php"
})

export class LoginComponent {
	@ViewChild("loginForm") loginForm : any;
	login : Login = new Login("", "");
	status : Status = null;

	constructor(private loginService: LoginService, private router: Router) {}

	signin() : void {
		this.loginService.login(this.login)
			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {
					this.router.navigate(["/"]);
				}
			});
	}
}
