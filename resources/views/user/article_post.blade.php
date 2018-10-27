@extends('layouts.base_template')

@section('title', 'Post')

@section('links')
	<link rel="stylesheet" href="/user/css/single_article.css">
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
                                    <i class="fa fa-clock-o"></i>
                                    <a >{{ date('d M Y', strtotime($articlePost->created_at)) }}</a>
                                </span>
                                <span>
                                    <i class="fa fa-folder-o"></i>
                                    <a href="/categories/{{ $articlePost->category_id }}">{{ $articlePost->category_name }}</a>
                                </span>
                            </div>
                            <div class="singl-article-details">
                                {!! $articlePost->article_text !!}
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
		</section>
		<hr>
	</div>

@endsection

@section('scripts')
<script>
	$(function() {

	});
</script>
@endsection