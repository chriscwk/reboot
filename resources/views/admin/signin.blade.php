<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot Admin">
	<meta name="author" content="Reboot Admin">

	<title>Sign In | Reboot Admin</title>

	@include('layouts.admin_links')
</head>
<body>
	<div class="container-scroller">
		<div class="container-fluid page-body-wrapper full-page-wrapper">
	      	<div class="content-wrapper d-flex align-items-center auth">
		        <div class="row w-100">
		          	<div class="col-lg-4 mx-auto">
			            <div class="auth-form-light text-left p-5">
			              	<h4>REBOOT ADMIN</h4>
			              	<form method="POST" action="{{ action('AdminController@sign_in') }}" class="pt-3">
								@csrf
			                	<div class="form-group">
			                  		<input type="username" name="username" class="form-control" placeholder="Username">
			                	</div>
			                	<div class="form-group">
			                  		<input type="password" name="password" class="form-control" placeholder="Password">
			                	</div>
			                	<div class="mt-3">
			                  		<button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" href="">SIGN IN</button>
			                	</div>
			              	</form>
			            </div>
		          	</div>
		        </div>
	      	</div>
	      <!-- content-wrapper ends -->
	    </div>
	    
		@include('layouts.admin_scripts')

		<script>
			$(function() {
				 @if(session('msg_status'))
					swal({
						html: '{!! session('msg_status') !!}',
						type: '{{ session('msg_class') }}',
						confirmButtonText: 'Ok'
					});
				@endif
			});
		</script>

		@yield('scripts')
	</div>
</body>
</html>