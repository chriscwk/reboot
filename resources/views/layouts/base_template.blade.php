<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot">
	<meta name="author" content="Reboot Admin">

	<title>@yield('title') | Reboot</title>

	@include('layouts.base_links')

	@yield('links')
</head>
<body>
	@include('layouts.nav_bar')

	@yield('content')

	{{-- <div class="page-footer">
		
	</div> --}}

	<div class="signin-overlay">
		<div class="signin-container">
			<div class="signin-header">SIGN IN<span id="close_signin" class="pull-right"><i class="fas fa-times"></i></span></div>
			<div class="signin-body">
				<a href="javascript:;" class="btn btn-facebook w-100">Sign In with Facebook</a>
				<hr>
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
				<a href="javascript:;" class="btn btn-facebook w-100">Sign Up with Facebook</a>
				<hr>
				<form id="signup_form" method="POST" action="{{ action('NormalController@sign_up') }}">
					@csrf
				
					<div class="row">
						<div class="col-xl-12">
							<div class="form-group">
								<label>Email</label>
								<input type="email" class="form-control" name="user_email" placeholder="Email" required />
								<span class="text-danger"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group">
								<label>Password</label>
								<input type="password" class="form-control" name="user_pass" placeholder="Password" required />
								<span class="text-danger"></span>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group">
								<label>Re-enter Password</label>
								<input type="password" class="form-control" name="user_pass2" placeholder="Password Again" required />
								<span class="text-danger"></span>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group">
								<label>First Name</label>
								<input type="text" class="form-control" name="user_first" placeholder="First Name" required />
								<span class="text-danger"></span>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group">
								<label>Last Name</label>
								<input type="text" class="form-control" name="user_last" placeholder="Last Name" required />
								<span class="text-danger"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-6">
							<div class="form-group">
								<label>Phone Number</label>
								<input type="text" class="form-control" name="user_phone" placeholder="Phone Number" required />
								<span class="text-danger"></span>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="form-group">
								<label>Birth Date</label>
								<input type="text" class="form-control" name="user_birth" placeholder="dd/mm/yyyy" required />
								<span class="text-danger"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-12 text-center">
							<button type="button" class="btn btn-outline-secondary m-t-20 btn-signup">SIGN UP</button>
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
			$('input[name="user_birth"]').mask("00/00/0000", {placeholder: "dd/mm/yyyy"});
			$('input[name="user_phone"]').mask('(+6) 000-00000000');

			$('.btn-signup').on('click', function() {
				var valid_signup = true;

				var namePattern = /^[A-Za-z]+$/;
				var passPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;

				var firstNameElem = $('input[name="user_first"]');
				var lastNameElem  = $('input[name="user_last"]');
				var passElem 	  = $('input[name="user_pass"]');
				var rePassElem    = $('input[name="user_pass2"]');

				var firstNameTest = namePattern.test(firstNameElem.val());
				var lastNameTest  = namePattern.test(lastNameElem.val());
				var passTest 	  = passPattern.test(passElem.val());
				var rePassTest    = passPattern.test(rePassElem.val());

				if(!firstNameTest) {
					firstNameElem.css('border-color', '#ff0000');
					firstNameElem.next().html('Invalid first name.');
					valid_signup = false;
				} else {
					firstNameElem.css('border-color', '#ced4da');
					firstNameElem.next().html('');
				}

				if(!lastNameTest) {
					lastNameElem.css('border-color', '#ff0000');
					lastNameElem.next().html('Invalid last name.');
					valid_signup = false;
				} else {
					lastNameElem.css('border-color', '#ced4da');
					lastNameElem.next().html('');
				}

				if(passElem.val() != rePassElem.val()) {
					passElem.css('border-color', '#ff0000');
					passElem.next().html('Passwords do not match.');
					rePassElem.css('border-color', '#ff0000');
					rePassElem.next().html('Passwords do not match.');
					valid_signup = false;
				} else if(!passTest) {
					passElem.css('border-color', '#ff0000');
					passElem.next().html('Invalid password format.');
					valid_signup = false;
				} else if(!rePassTest) {
					rePassElem.css('border-color', '#ff0000');
					rePassElem.next().html('Invalid password format.');
					valid_signup = false;
				} else {
					passElem.css('border-color', '#ced4da');
					passElem.next().html('');
					rePassElem.css('border-color', '#ced4da');
					rePassElem.next().html('');
				}

				if(valid_signup) {
					$(this).prop('type', 'submit');
					$(this).trigger('click');
				}
			});

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