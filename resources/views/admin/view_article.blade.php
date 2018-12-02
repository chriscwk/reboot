<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="Reboot">
	<meta name="author" content="Reboot Admin">

	<title>Article Preview | Reboot</title>

	<link rel="stylesheet" href="/user/css/general.css">
	<link rel="stylesheet" href="/user/css/navbar.css">

	<style>
		#content-previewer{
			border: 2px solid black;
		}
		.read-more {
			margin-top: 30px;
			font-size: 36px;
		}
	</style>
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

		@if($article->article_link != "")
		<div id="content-previewer">{!! $link_preview !!}</div>
		<div class="read-more text-center">Read more <a href="{{ $article->article_link }}" target="_blank">here</a></div>
		@else
		<div style="overflow: auto;">
		{!! $article->article_text !!}
		</div>
		@endif
		
	</div>
</body>
</html>