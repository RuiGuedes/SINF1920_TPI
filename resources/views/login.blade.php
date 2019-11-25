@extends('app')

@section('title', 'Login')
	
@section('body')
	<div class="login-container container-fluid">
		<div class="login-logo row align-items-end">
			<div class="logo-line col"></div>
			<div class="company-name-login col-6 col-sm-5 col-md-3 col-xl-2 text-center">TPI</div>
			<div class="logo-line col"></div>
		</div>

		<div class="login-fields row justify-content-center">
			<div class="col-10 col-sm-6 col-md-4 col-xl-3">
				<form id="login-form" action="{{ route($route) }}">
					<div class="form-group">
						<label for="username" hidden>Username</label>
						<input type="text" class="form-control" id="username" placeholder="Username">
					</div>
					<div class="form-group">
						<label for="password" hidden>Password</label>
						<input type="password" class="form-control" id="password" placeholder="Password">
					</div>
					<div class="row justify-content-center">
						<button type="submit" class="btn btn-secondary">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection