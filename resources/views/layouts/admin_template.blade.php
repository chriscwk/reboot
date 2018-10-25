<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot Admin">
	<meta name="author" content="Reboot Admin">

	<title>@yield('title') | Reboot Admin</title>

	@include('layouts.admin_links')
</head>
<body>
	<div class="container-scroller">
		@include('layouts.admin_top_nav_bar')

		<div class="container-fluid page-body-wrapper">
			@include('layouts.admin_side_nav_bar')
			@yield('content')
		</div>

		@include('layouts.admin_scripts')

		<script>
			$(function() {
			    $('[data-toggle="offcanvas"]').on("click", function() {
			      	$('.sidebar-offcanvas').toggleClass('active')
			    });
			  });
		</script>

		@yield('scripts')
	</div>
</body>
</html>