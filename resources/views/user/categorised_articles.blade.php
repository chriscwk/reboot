@extends('layouts.base_template')

@section('title', $category->category_name.' Articles')

@section('links')
<link rel="stylesheet" href="/user/css/article.css">
@endsection

@section('content')

    <div class="main-container">
		<div class="section-header">
			<h3>{{$category->category_name}} Articles</h3>
		</div>
		<div class="row">
            <div class="col-xl-3">
				<section id="month-container">
					<div class="list-group">
						<button type="button" class="list-group-item d-flex justify-content-between align-items-center month-selector {{ (!isset($_GET['month'])) ? 'active' : '' }}" data-id="">
							All
						</button>
						@foreach($monthList as $key => $value)
						<button type="button" class="list-group-item d-flex justify-content-between align-items-center month-selector {{ (isset($_GET['month']) && ($_GET['month'] == date('Ym', strtotime($key)))) ? 'active' : '' }}" data-id="{{ date('Ym', strtotime($key)) }}">
							{{ $key }}
							<span class="badge badge-info badge-pill">{{ $value }}</span>
						</button>
						@endforeach
					</div>
                </section>
                <hr>
            </div>
            
			<div class="col-xl-9">
				<section id="article-container"></section>

				<div class="text-center custom-spinner">
					<i class="fa fa-spin fa-spinner fa-10x"></i>
					<div class="custom-spinner-text">loading...</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
<script>
	var current = 0;
		
    //debounce 
    var scrolled = false;
    $(window).scroll(function () {
        toScroll = $(document).height() - $(window).height() - 30;
        if ($(this).scrollTop() > toScroll) {
            if (!scrolled) {
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
        
        $(document).on('click', '.month-selector', function (e) {
			if ($(this).attr('data-id') == "")
				window.location.href = window.location.href.split('?')[0];
			else
				window.location.href = '?month=' + $(this).attr('data-id');
		});
    });

    function retrieve_article() {
        var month = getQueryStringParam("month") === "null" ? "" : getQueryStringParam("month");

        $.ajax({
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            type: 'POST',
            url: '/articles/getApprovedArticleByPage',
            data: {
                'current': current,
                'categoryId': {{ $category-> id }},
                'month' : month
            },
            success: function (data) {
                current = current + data.length;
                data = '{article:' + JSON.stringify(data) + '}';
                data = eval('(' + data + ')');
                $.get('/user/template/tpArticleCard.htm', function (template) {
                    jQuery(template).tmpl(data).appendTo('#article-container');
                });
            },
            error: function (data) {
                $.ajax(this);
                return;
            }
        }).done(function () {
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
