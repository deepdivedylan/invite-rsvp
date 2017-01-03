import {RouterModule, Routes} from "@angular/router";
import {LoginComponent} from "./components/login-component";
import {RsvpComponent} from "./components/rsvp-component";
import {SplashComponent} from "./components/splash-component";


export const allAppComponents = [LoginComponent, RsvpComponent, SplashComponent];

export const routes: Routes = [
	{path: "login", component: LoginComponent},
	{path: "rsvp/:inviteeToken", component: RsvpComponent},
	{path: "", component: SplashComponent}
];

export const appRoutingProviders: any[] = [];
export const routing = RouterModule.forRoot(routes);
