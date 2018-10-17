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

	@include('layouts.base_scripts')
</body>
</html>