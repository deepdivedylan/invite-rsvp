import {RouterModule, Routes} from "@angular/router";
import {LoginComponent} from "./components/login-component";
import {SplashComponent} from "./components/splash-component";


export const allAppComponents = [LoginComponent, SplashComponent];

export const routes: Routes = [
	{path: "login", component: LoginComponent},
	{path: "", component: SplashComponent}
];

export const appRoutingProviders: any[] = [];
export const routing = RouterModule.forRoot(routes);
