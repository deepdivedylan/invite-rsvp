import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {Observable} from "rxjs/Observable";
import {BaseService} from "./base-service";
import {Rsvp} from "../classes/rsvp";
import {Status} from "../classes/status";

@Injectable()
export class RsvpService extends BaseService {
	constructor(protected http: Http) {
		super(http);
	}

	private rsvpUrl = "api/rsvp/";

	getAllRsvps() : Observable<Rsvp[]> {
		return(this.http.get(this.rsvpUrl)
			.map(this.extractData)
			.catch(this.handleError));
	}

	getRsvpByInviteeToken(inviteeToken: string) : Observable<Rsvp> {
		return(this.http.get(this.rsvpUrl + "?inviteeToken=" + inviteeToken)
			.map(this.extractData)
			.catch(this.handleError));
	}

	createRsvp(rsvp: Rsvp) : Observable<Status> {
		return(this.http.post(this.rsvpUrl, rsvp)
			.map(this.extractMessage)
			.catch(this.handleError));
	}
}
