@extends('layouts.base_template')

@section('title', $articlePost->article_title)

@section('links')
	<link rel="stylesheet" href="/user/css/single_article.css">
    <style>
		#content-previewer{
			border: 2px solid black;
		}
	</style>
@endsection

@section('content')

	<div class="main-container">
		<section>
			<div class="row">
                <div class="col-xl-12">
                    <div class="article-img">
                        <img src="/user/articles/{{ $articlePost->article_img }}" alt="article-img">
                    </div>
                    <div class="singl-article-post">
                        <figure>
                            <div class="singl-article-title" >
                                <h3>
                                    {{ $articlePost->article_title }}
                                    <a href="javascript:;" class="btn-fb-share" target="popup" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=http://reboot.epizy.com/articles/{{ $articlePost->id }}','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;">Share on FB</a>
                                </h3>
                            </div>
                            <div class="singl-article-status-bar">
                                <span>
                                    <i class="fa fa-user"></i>
                                    <a >{{ $articlePost->author }}</a>
                                </span>
                                <span>
                                    <i class="fas fa-clock-o"></i>
                                    <a >{{ date('d M Y', strtotime($articlePost->created_at)) }}</a>
                                </span>
                                <span>
                                    <i class="fa fa-folder-o"></i>
                                    <a href="/categories/{{ $articlePost->category_id }}">{{ $articlePost->category_name }}</a>
                                </span>
                                <span id="article-ratings">
                                        <i class="fas fa-thumbs-up rating-icon {{$userRating == 1 ? 'text-info' : ''}}" title="I like this"></i>
                                        <label id="up-ratings" class="rating-count">{{ $articleRatings->positive_ratings }}</label>
                                        <i class="fas fa-thumbs-down rating-icon {{$userRating == -1 ? 'text-info' : ''}}" title="I dislike this"></i>
                                        <label id="down-ratings" class="rating-count">{{ $articleRatings->negative_ratings }}</label>
                                    @if(\Auth::check())
                                        <div class="separator"></div>
                                        @if($articlePost->is_favorited != "")
                                        <i class="fas fa-heart fav-icon text-danger rating-icon"></i>
                                        @else
                                        <i class="fas fa-heart fav-icon rating-icon"></i>
                                        @endif
                                    @endif
                                </span>
                            </div>
                            <div class="singl-article-details">

                                @if($articlePost->article_link != "")
                                <div id="content-previewer">{!! $link_preview !!}</div>
                                <div class="read-more text-center">Read more <a href="{{ $articlePost->article_link }}" target="_blank">here</a></div>
                                @else
                                {!! $articlePost->article_text !!}
                                @endif

                            </div>
                        </figure>
                    </div>
                </div>
            </div>

		</section>
		<hr>

        <form id="comment_form" method="POST" action="{{ action('ArticleController@addComment') }}" enctype="multipart/form-data">
            @csrf
            <section id="comment-section">
                <h4>Comment Section</h4>
                <p>Posted comments are not removable. Ensure your comments meet our policy. </p>
                <input type="hidden" name="article_id" value="{{ $articleId }}" />
                <div class="row">
                    <div class="col-xl-10">
                        <textarea id="comment-area" name="comment_area"></textarea>
                    </div>
                    <div class="col-xl-2">
                        <button type="button" class="btn btn-info btn-block btn-submit" style="height:100%;">
                            <i class="fas fa-paper-plane fa-2x"></i>
                        </button>
                    </div>
                </div>
                <br>
                <div class="row">
					<div class="col-xl-12">
                        <table class="table">
                            @foreach ($articleComments as $comment)
                            <tr>
                                <td><strong>{{$comment->created_by}}</strong> says</td>
                                <td>{{$comment->comment_text}}</td>
                                <td class="text-right">at <strong>{{ date('d/m h:i A', strtotime($comment->created_at)) }}</strong></td>
                            <tr>
                            @endforeach
                        </table>
					</div>
					<div class="col-xl-12">
                        {!! $articleComments->fragment('comment-section')->render() !!}
                    </div>
                </div>
            </section>
        </form>
       
	</div>

@endsection

@section('scripts')
<script type="text/javascript">
    var article_id = $('input[name="article_id"]').val();
	$(document).ready(function () {
        $('.btn-submit').on('click', function() {
            if ("{{\Auth::check()}}") {
                if ($("#comment-area").val().trim() != "") {
                    $("#comment_form").submit();
                }
                else {
                    swal({
                        text: "Try putting in some comments.",
                        type: "warning",
                        confirmButtonText: "Ok"
				    });
                }
            }
            else {
                swal({
					text: "You have to be logged in to post comments.",
					type: "error",
					confirmButtonText: "Ok"
				});
            }
		});

        $(document).on('click', '.fav-icon', function (e) {
			var isFavorited = $(this).hasClass("text-danger");
			if (isFavorited) {
				$(this).removeClass("text-danger");
			}
			else {
				$(this).addClass("text-danger");
			}
			isFavorited = !isFavorited;
			favorite_article(isFavorited, article_id);
        });
        
        $(document).on('click', 'i.fa-thumbs-up', function (e) {
            tilt_rating_display($(this));
        });

        $(document).on('click', 'i.fa-thumbs-down', function (e) {
            tilt_rating_display($(this));
		});
    });
    
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
    
    function tilt_rating_display(thumb) {
        if ("{{\Auth::check()}}") {
            var isRate = false;
            var caller = thumb.hasClass("fa-thumbs-up") ? "up" : "down";
            var other = caller == "up" ? "down" : "up";

            var callerRating = parseInt($("#" + caller + "-ratings").text());
            var otherRating = parseInt($("#" + other + "-ratings").text());

            if (thumb.hasClass("text-info")) { //has already been thumbed
                thumb.removeClass("text-info");
                $("#" + caller + "-ratings").text(callerRating - 1);

                isRate = false; //un-thumbed
            }
            else { //hasn't been thumbed
                thumb.addClass("text-info");
                $("#" + caller + "-ratings").text(callerRating + 1);

                if ($("i.fa-thumbs-" + other).hasClass("text-info")) {
                    $("i.fa-thumbs-" + other).removeClass("text-info");
                    $("#" + other + "-ratings").text(otherRating - 1);
                }

                isRate = true; //thumbed
            }
            tilt_rating(isRate, caller);
        }
        else {
            swal({
                text: "You have to be logged in to rate articles.",
                type: "error",
                confirmButtonText: "Ok"
            });
        }
    }

    function tilt_rating(isRate, rating) {
        $.ajax({
			headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
			type: 'POST',
			url: '/articles/rateArticle',
			data: { 'article_id' : article_id, 'isRate' : isRate, 'rating' : rating },
			success: function(data) {
				if (isRate) {
                    if (rating == "up") {
                        toastr.info('You liked this article');
                    }
                    else {
                        toastr.info('You disliked this article');
                    }
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