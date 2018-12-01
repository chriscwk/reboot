@extends('layouts.base_template')

@section('title', 'Home')

@section('links')
	<link rel="stylesheet" href="/user/css/article.css">
@endsection

@section('content')

	<div class="main-container">
		<section id="article-container">
			<div class="section-header">
				<h3>Home</h3>
			</div>
		</section>

		<div class="text-center custom-spinner">
			<i class="fa fa-spin fa-spinner fa-10x"></i>
			<div class="custom-spinner-text">loading...</div>
		</div>
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

		$(document).on('click', '.card', function () {
            window.location.href = $(this).attr('data-url');
        });
	
		$(document).on('click', '.article-favorite', function (e) {
			var isFavorited = $(this).hasClass("btn-danger");
			if (isFavorited) {
				$(this).removeClass("btn-danger");
			}
			else {
				$(this).addClass("btn-danger");
			}
			isFavorited = !isFavorited;
			var article_id = $(this).next().attr('data-id');
			favorite_article(isFavorited, article_id);
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
			$(".custom-spinner").hide();
		});
	}
	
	function favorite_article(isFavorited, article_id) {
		$.ajax({
			headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
			type: 'POST',
			url: '/articles/favoriteArticle',
			data: { 'article_id' : article_id, 'isFavorited' : isFavorited },
			success: function(data) {
				if (isFavorited) {
                    toastr.info('You just favorited the article!');
                }
                else {
                    toastr.info('You just unfavorited the article!');
                }
			},
			error: function(data) {
				$.ajax(this);
				return;
			}
		});
	}
</script>
@endsection