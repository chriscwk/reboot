@extends('layouts.base_template')

@section('title', 'Home')

@section('links')
	<link rel="stylesheet" href="/user/css/article.css">
@endsection

@section('content')

	<div class="main-container">
		<section id="article-container">

		</section>
	</div>

@endsection

@section('scripts')
<script>
	var current = 0;

	//debounce 
	var scrolled = false;
	$(window).scroll(function(){
		toScroll = $(document).height() - $(window).height() - 30;
		if ( $(this).scrollTop() > toScroll ) {
			if(!scrolled) {
				scrolled = true;
				retrieve_article();
			}
		}
	});

	$(document).ready(function () {
		retrieve_article();

		$('.card').on('click', function() {
			$('#article_id').val($(this).attr('data-id'));
			//$('#edit_article_form').submit();
		});

	});
	
	function retrieve_article() {
		$.ajax({
			headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
			type: 'POST',
			url: '/articles/getApprovedArticleByPage',
			data: { 'current' : current },
			success: function(data) {
				current = current + data.length;
				data = '{article:' + JSON.stringify(data) + '}';
				data = eval('(' + data + ')');
				$.get('/user/template/tpArticleCard.htm', function (template) {
					jQuery(template).tmpl(data).appendTo('#article-container');
				});
			},
			error: function(data) {
				$.ajax(this);
				return;
			}
		}).done(function() {
			scrolled = false; //reset debounce
		});
	}

</script>
@endsection