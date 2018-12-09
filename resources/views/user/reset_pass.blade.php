<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot">
	<meta name="author" content="Reboot Admin">

	<title>Reset Password | Reboot</title>

	<link rel="stylesheet" 
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
	integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
	crossorigin="anonymous">

	<style>
		html, body {
			width: 100%;
			margin: 0;
			padding: 0;
		}

		.reset-container {
			width: 30%;
			margin-top: 25%;
			margin-left: 50%;
			transform: translate(-50%, -50%);
		}
	</style>
</head>
<body>

	<div class="reset-container">
		<form method="POST" action="{{ action('NormalController@change_pass') }}">
			@csrf
		
			<div class="row">
				<div class="col-xl-12">
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="password" placeholder="Password" required />
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="col-xl-12">
					<div class="form-group">
						<label>Re-enter Password</label>
						<input type="password" class="form-control" name="repassword" placeholder="Re-enter Password" required />
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="col-xl-12 text-center">
					<button id="change_pass" type="button" class="btn btn-outline-secondary m-t-20">Change Password</button>
				</div>

				<input type="hidden" name="userID" value="{{ $userID }}" />
			</div>
		</form>
	</div>

	<script
  	src="https://code.jquery.com/jquery-3.3.1.min.js"
  	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  	crossorigin="anonymous"></script>
  	<script 
	src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
	integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
	crossorigin="anonymous"></script>
	<script 
		src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" 
		integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" 
		crossorigin="anonymous"></script>
	<script 
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
		integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
		crossorigin="anonymous"></script>

	<script>
		$(function() {
			$('#change_pass').on('click', function() {
				var valid_signup = true;

				var passPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;

				var passElem 	  = $('input[name="password"]');
				var rePassElem    = $('input[name="repassword"]');

				var passTest 	  = passPattern.test(passElem.val());
				var rePassTest    = passPattern.test(rePassElem.val());

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
		});
	</script>
</body>
</html>