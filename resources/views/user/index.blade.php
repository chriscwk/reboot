@extends('layouts.base_template')

@section('title', 'Home')

@section('content')

	<div class="bg-first"></div>
	<div class="bg-second"></div>
	<div class="bg-third"></div>

	<div class="top-content">

	</div>
	<div class="categories-content">

	</div>
	<div class="all-content">
		
	</div>

	<div class="page-footer">
		<div class="su-container hidden">
			<i class="fas fa-angle-double-up"></i>
			<div>scroll up</div>
		</div>
		<div class="sd-container">
			<i class="fas fa-angle-double-down"></i>
			<div>scroll down</div>
		</div>
	</div>

	<div class="login-overlay">
		<div class="login-container">
			<div class="login-header">LOG IN<span id="close_login" class="pull-right"><i class="fas fa-times"></i></span></div>
			<form method="POST" action="{{ action('LoginController@login') }}">
				@csrf

				<div class="login-body">
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
							<button type="submit" class="btn btn-outline-secondary m-t-20">Log In</button>
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
	    	var currDisplay = 'first';

	    	$('body').on('mousewheel DOMMouseScroll', function(e){
	    		e.preventDefault();

				if(typeof e.originalEvent.detail == 'number' && e.originalEvent.detail !== 0) {
				    if(e.originalEvent.detail > 0) {
				      	scrollDown();
				    } else if(e.originalEvent.detail < 0){
				        scrollUp();
				    }
				} else if (typeof e.originalEvent.wheelDelta == 'number') {
				    if(e.originalEvent.wheelDelta < 0) {
				    	scrollDown();
				    } else if(e.originalEvent.wheelDelta > 0) {
				    	scrollUp();
				    }
				}
			});

			$('#home').on('click', function() {
				$('nav, .page-footer').fadeOut(10, function() {
	    			scrollTo('.bg-first');
	    			currDisplay = 'first';

	    			$('.su-container').addClass('hidden');
	    			$('.sd-container').removeClass('hidden');
	    		});
			});

			$('#categories').on('click', function() {
				$('nav, .page-footer').fadeOut(10, function() {
					scrollTo('.bg-second');
	    			currDisplay = 'second';

	    			$('.su-container').removeClass('hidden');
	    			$('.sd-container').removeClass('hidden');
				});
			});

			$('#aboutUs').on('click', function() {
				$('nav, .page-footer').fadeOut(10, function() {
	    			scrollTo('.bg-third');
	    			currDisplay = 'third';

	    			$('.su-container').removeClass('hidden');
	    			$('.sd-container').addClass('hidden');
	    		});
			});

			$('#login').on('click', function() {
				$('.login-overlay').addClass('show');
				$('.login-container').delay( 500 ).fadeIn();
			});

			$('#close_login').on('click', function() {
				$('.login-container').fadeOut(150, function() {
					$('.login-overlay').removeClass('show');
				});
			});

			function scrollTo(position) {
				$('nav, .page-footer').addClass('fixed-nav');
		    	$('.top-content').css('margin-top', '7.5vh');
		    	
				$('html, body').animate({
                    scrollTop: $(position).offset().top
                }, 500, function() {
                	$('nav, .page-footer').fadeIn();
                });
			}

			function scrollDown() {
				if(currDisplay == 'first') {
					$('nav, .page-footer').fadeOut(10, function() {
						scrollTo('.bg-second');
		    			currDisplay = 'second';

		    			$('.su-container').removeClass('hidden');
					});
		    	} else if(currDisplay == 'second') {
		    		$('nav, .page-footer').fadeOut(10, function() {
		    			scrollTo('.bg-third');
		    			currDisplay = 'third';

		    			$('.sd-container').addClass('hidden');
		    		});
		    	}
			}

			function scrollUp() {
				if(currDisplay == 'third') {
					$('nav, .page-footer').fadeOut(10, function() {
						scrollTo('.bg-second');
		    			currDisplay = 'second';

		    			$('.sd-container').removeClass('hidden');
					});
		    	} else if(currDisplay == 'second') {
		    		$('nav, .page-footer').fadeOut(10, function() {
		    			scrollTo('.bg-first');
		    			currDisplay = 'first';

		    			$('.su-container').addClass('hidden');
		    		});
		    	}
			}
	    });
	</script>
@endsection