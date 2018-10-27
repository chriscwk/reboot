@extends('layouts.base_template')

@section('title', $category->category_name.' Articles')

@section('links')
	<link rel="stylesheet" href="/user/css/article.css">
@endsection

@section('content')

	<div class="main-container">
        <section id="article-container">
            <div class="section-header">
				<h3>{{$category->category_name}} Articles</h3>
			</div>
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

		$(document).on('click', '.card', function () {
            window.location.href = $(this).attr('data-url');
        });

	});
	
	function retrieve_article() {
		$.ajax({
			headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
			type: 'POST',
			url: '/articles/getApprovedArticleByPage',
			data: { 'current' : current, 'categoryId' : {{ $category->id }} },
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