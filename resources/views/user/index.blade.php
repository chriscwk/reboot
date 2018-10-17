@extends('layouts.base_template')

@section('title', 'Home')

@section('content')

	<div class="bg-first"></div>
	<div class="bg-second"></div>
	<div class="bg-third"></div>

	<div class="main-content">
		<div class="top-content">

		</div>
		<div class="categories-content">

		</div>
		<div class="all-content">
			
		</div>
	</div>

	<div class="page-footer">
		<div class="sd-container">
			<i class="fas fa-angle-double-down"></i>
			<div>scroll down</div>
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
				$('nav').fadeOut(10, function() {
	    			scrollTo('.bg-first');
	    			currDisplay = 'first';
	    		});
			});

			$('#categories').on('click', function() {
				$('nav').fadeOut(10, function() {
					scrollTo('.bg-second');
	    			currDisplay = 'second';
				});
			});

			$('#aboutUs').on('click', function() {
				$('nav').fadeOut(10, function() {
	    			scrollTo('.bg-third');
	    			currDisplay = 'third';
	    		});
			});

			function scrollTo(position) {
				$('nav').addClass('fixed-nav');
		    	$('.top-content').css('margin-top', '7.5vh');
		    	
				$('html, body').animate({
                    scrollTop: $(position).offset().top
                }, 500, function() {
                	$('nav').fadeIn();
                });
			}

			function scrollDown() {
				if(currDisplay == 'first') {
					$('nav').fadeOut(10, function() {
						scrollTo('.bg-second');
		    			currDisplay = 'second';
					});
		    	} else if(currDisplay == 'second') {
		    		$('nav').fadeOut(10, function() {
		    			scrollTo('.bg-third');
		    			currDisplay = 'third';
		    		});
		    	}
			}

			function scrollUp() {
				if(currDisplay == 'third') {
					$('nav').fadeOut(10, function() {
						scrollTo('.bg-second');
		    			currDisplay = 'second';
					});
		    	} else if(currDisplay == 'second') {
		    		$('nav').fadeOut(10, function() {
		    			scrollTo('.bg-first');
		    			currDisplay = 'first';
		    		});
		    	}
			}
	    });
	</script>
@endsection