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
                                <h3>{{ $articlePost->article_title }}</h3>
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
                            </div>
                            <div class="singl-article-details">

                                @if($articlePost->article_link != "")
                                <div id="content-previewer">{!! $link_preview !!}</div>
                                <div class="read-more text-center">Read more <a href="{{ $articlePost->article_link }}">here</a></div>
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
					    {{ $articleComments->links() }}
                    </div>
                </div>
            </section>
        </form>
       
	</div>

@endsection

@section('scripts')
<script>
	$(function() {
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
					text: "You have to be logged in to post your comments.",
					type: "error",
					confirmButtonText: "Ok"
				});
            }
		});
	});
</script>
@endsection