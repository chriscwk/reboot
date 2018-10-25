<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot">
	<meta name="author" content="Reboot Admin">

	<title>Article Preview | Reboot</title>

	<link rel="stylesheet" href="/user/css/general.css">
	<link rel="stylesheet" href="/user/css/navbar.css">
</head>
<body>
	<div class="cust-container">
		<nav>
			<input type="checkbox" id="nav" class="hidden">
			<label for="nav" class="nav-btn">
				<i></i>
				<i></i>
				<i></i>
			</label>
			<div class="logo">
				<a href="javascript:;">REBOOT</a>
			</div>
		</nav>
	</div>

	<div class="main-container">
		<h3 style="text-align: center;">{{ $article->article_title }}</h3>
		<hr>
		{!! $article->article_text !!}
	</div>
</body>
</html>