import {Component} from "@angular/core";

@Component({
	selector: 'invite-rsvp-app',
	templateUrl: './templates/invite-rsvp-app.php'
})

export class AppComponent {
	navCollapse = true;

	toggleCollapse() {
		this.navCollapse = !this.navCollapse;
	}
}
