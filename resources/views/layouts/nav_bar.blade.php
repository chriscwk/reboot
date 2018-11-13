<div class="cust-container">
	<nav>
		<input type="checkbox" id="nav" class="hidden">
		<label for="nav" class="nav-btn">
			<i></i>
			<i></i>
			<i></i>
		</label>
		<div class="logo">
			<a href="{{ action('NormalController@index') }}">REBOOT</a>
		</div>
		<div class="nav-wrapper">
			<ul>
				<li><a id="home" href="{{ action('NormalController@index') }}">Home</a></li>
				<li><a id="categories" href="{{ action('CategoryController@index') }}">Categories</a></li>
				{{-- <li><a id="aboutUs" href="javascript:;">About Us</a></li> --}}
				@if(\Auth::check())
				<li>
					<a href="javascript:;"><img class="user-img" src="http://icons-for-free.com/free-icons/png/512/1287507.png" />{{ Auth::user()->first_name }}</a>
				</li>
					@if(\Auth::user()->publisher == 1)
					<li><a href="{{ action('ArticleController@index') }}">My Articles</a></li>
					<li><a href="{{ action('EventController@index') }}">Organize Meetups</a></li>
					@endif
				{{-- <li><a href="javascript:;">Profile</a></li> --}}
				<li><a href="{{ action('NormalController@sign_out') }}">Sign Out</a></li>
				@else
				<li><a id="signin" href="javascript:;">Sign In</a></li>
				<li><a id="signup" href="javascript:;">Sign Up</a></li>
				@endif
			</ul>
		</div>
	</nav>
</div>