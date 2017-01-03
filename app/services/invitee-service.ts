import {Injectable} from "@angular/core";
import {Http} from "@angular/http";
import {Observable} from "rxjs/Observable";
import {BaseService} from "./base-service";
import {Invitee} from "../classes/invitee";
import {Status} from "../classes/status";

@Injectable()
export class InviteeService extends BaseService {
	constructor(protected http: Http) {
		super(http);
	}

	private inviteeUrl = "api/invitee/";

	deleteInvitee(inviteeId: number) : Observable<Status> {
		return(this.http.delete(this.inviteeUrl + inviteeId)
			.map(this.extractMessage)
			.catch(this.handleError));
	}

	getAllInvitees() : Observable<Invitee[]> {
		return(this.http.get(this.inviteeUrl)
			.map(this.extractData)
			.catch(this.handleError));
	}

	getInvitee(inviteeId: number) : Observable<Invitee> {
		return(this.http.get(this.inviteeUrl + inviteeId)
			.map(this.extractData)
			.catch(this.handleError));
	}

	getInviteeByInviteeToken(inviteeToken: string) : Observable<any> {
		return(this.http.get(this.inviteeUrl + "?inviteeToken=" + inviteeToken)
			.map(this.extractData)
			.catch(this.handleError));
	}

	createInvitee(invitee: Invitee) : Observable<Status> {
		return(this.http.post(this.inviteeUrl, invitee)
			.map(this.extractMessage)
			.catch(this.handleError));
	}

	editInvitee(invitee: Invitee) : Observable<Status> {
		return(this.http.put(this.inviteeUrl + invitee.inviteeId, invitee)
			.map(this.extractMessage)
			.catch(this.handleError));
	}
}
