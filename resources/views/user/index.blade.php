@extends('layouts.base_template')

@section('title', 'Home')

@section('content')

	<div class="main-container">

	</div>

	<div class="page-footer">
		
	</div>

	<div class="login-overlay">
		<div class="login-container">
			<div class="login-header">SIGN IN<span id="close_login" class="pull-right"><i class="fas fa-times"></i></span></div>
			<form method="POST" action="{{ action('LoginController@login') }}">
				@csrf

				<div class="login-body">
					<div class="login-email">
						<div class="row">
							<div class="col-xl-12">
								<div class="form-group">
									<label>Email</label>
									<input type="text" class="form-control" name="user_email" placeholder="Enter Email" required />
								</div>
							</div>
							<div class="col-xl-12">
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control" name="user_pass" placeholder="Enter Password" required />
								</div>
							</div>
							<div class="col-xl-12 text-center">
								<button type="submit" class="btn btn-outline-secondary m-t-20">SIGN IN</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

@endsection

@section('scripts')
	<script>

	    $(function() {
			$('#login').on('click', function() {
				$('.login-overlay').addClass('show');
				$('.login-container').delay( 500 ).fadeIn();
			});

			$('#close_login').on('click', function() {
				$('.login-container').fadeOut(150, function() {
					$('.login-overlay').removeClass('show');
				});
			});
	    });
	</script>
@endsection