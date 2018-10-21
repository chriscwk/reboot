<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot">
	<meta name="author" content="Reboot Admin">

	<title>@yield('title') - Reboot</title>

	@include('layouts.base_links')
</head>
<body>
	@include('layouts.nav_bar')

	@yield('content')

	<div class="page-footer">
		
	</div>

	<div class="signin-overlay">
		<div class="signin-container">
			<div class="signin-header">SIGN IN<span id="close_signin" class="pull-right"><i class="fas fa-times"></i></span></div>
			<div class="signin-body">
				<form method="POST" action="{{ action('NormalController@sign_in') }}">
					@csrf
				
					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<label>Email</label>
								<input type="text" class="form-control" name="email" placeholder="Enter Email" required />
							</div>
						</div>
						<div class="col-xl-12">
							<div class="form-group">
								<label>Password</label>
								<input type="password" class="form-control" name="password" placeholder="Enter Password" required />
							</div>
						</div>
						<div class="col-xl-12 text-center">
							<button type="submit" class="btn btn-outline-secondary m-t-20">SIGN IN</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="signup-overlay">
		<div class="signup-container">
			<div class="signup-header">SIGN UP<span id="close_signup" class="pull-right"><i class="fas fa-times"></i></span></div>
			<div class="signup-body">
				<form method="POST" action="{{ action('NormalController@sign_up') }}">
					@csrf
				
					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<label>Email</label>
								<input type="text" class="form-control" name="user_email" placeholder="Enter Email" required />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group">
								<label>Password</label>
								<input type="password" class="form-control" name="user_pass" placeholder="*********" required />
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group">
								<label>Re-enter Password</label>
								<input type="password" class="form-control" name="user_pass2" placeholder="*********" required />
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" class="form-control" name="user_first" placeholder="First Name" required />
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" class="form-control" name="user_last" placeholder="Last Name" required />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group">
								<label>Phone Number</label>
								<input type="text" class="form-control" name="user_phone" placeholder="Phone Number" required />
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group">
								<label>Birth Date</label>
								<input type="text" class="form-control" name="user_birth" placeholder="dd/mm/yyyy" required />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 text-center">
							<button type="submit" class="btn btn-outline-secondary m-t-20">SIGN UP</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="close-overlay"></div>

	@include('layouts.base_scripts')

	<script>
		$(function() {
			@if(session('msg_success'))
				swal({
					// title: '{{ ucfirst(session('msg_class')) }}!',
					html: '{!! session('msg_success') !!}',
					type: '{{ session('msg_class') }}',
					confirmButtonText: 'Ok'
				})
			@endif

			@if(session('msg_fail'))
				swal({
					// title: '{{ ucfirst(session('msg_class')) }}!',
					html: '{!! session('msg_fail') !!}',
					type: '{{ session('msg_class') }}',
					confirmButtonText: 'Ok'
				})
			@endif
		});
	</script>

	@yield('scripts')
</body>
</html>