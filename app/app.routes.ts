import {RouterModule, Routes} from "@angular/router";
import {SplashComponent} from "./components/splash-component";


export const allAppComponents = [SplashComponent];

export const routes: Routes = [
	{path: "", component: SplashComponent}
];

export const appRoutingProviders: any[] = [];
export const routing = RouterModule.forRoot(routes);
