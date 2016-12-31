<form #loginForm="ngForm" class="form-horizontal well" name="loginForm" id="loginForm" (ngSubmit)="signin();" novalidate>
	<h1>Sign In</h1>
	<div class="form-group" [ngClass]="{ 'has-error': loginUsername.touched && loginUsername.invalid }">
		<label for="loginUsername">Username</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-user" aria-hidden="true"></i>
			</div>
			<input type="text" id="loginUsername" name="loginUsername" placeholder="username" class="form-control" required [(ngModel)]="login.loginUsername" #loginUsername="ngModel" />
		</div>
		<div [hidden]="loginUsername.valid || loginUsername.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="loginUsername.errors?.required">Username is required.</p>
		</div>
	</div>
	<div class="form-group" [ngClass]="{ 'has-error': loginPassword.touched && loginPassword.invalid }">
		<label for="loginPassword">Password</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-key" aria-hidden="true"></i>
			</div>
			<input type="password" id="loginPassword" name="loginPassword" placeholder="password" class="form-control" required [(ngModel)]="login.loginPassword" #loginPassword="ngModel" />
		</div>
		<div [hidden]="loginPassword.valid || loginPassword.pristine" class="alert alert-danger" role="alert">
			<p *ngIf="loginPassword.errors?.required">Password is required.</p>
		</div>
	</div>
	<hr/>
	<button class="btn btn-lg btn-info" type="submit" [disabled]="loginForm.invalid"><i class="fa fa-sign-in"></i>&nbsp;Sign In</button>
	<button class="btn btn-lg btn-warning" type="reset"><i class="fa fa-ban"></i>&nbsp;Reset</button>
</form>
<div *ngIf="status !== null" class="alert alert-dismissible" [ngClass]="status.type" role="alert">
	<button type="button" class="close" aria-label="Close" (click)="status = null;"><span aria-hidden="true">&times;</span></button>
	{{ status.message }}
</div>
