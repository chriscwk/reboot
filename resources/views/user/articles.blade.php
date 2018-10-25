@extends('layouts.base_template')

@section('title', 'Articles')

@section('links')
	<link rel="stylesheet" href="/user/css/article.css">
@endsection

@section('content')

	<div class="main-container">
		<section>
			<div class="section-header">
				<h3>My Articles</h3>
				<a href="{{ action('ArticleController@create') }}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i></a>
			</div>

			<div class="row">
				@foreach($articles AS $article)
				<div class="col-xl-4">
					<button class="btn btn-danger btn-icon btn-rounded article-delete"><i class="fas fa-trash"></i></button>
					<div data-id="{{ $article->id }}" class="card article-card">
						<img class="article-img" src="/user/articles/{{ $article->article_img }}" />
						<div class="card-body">
							@if($article->verified == 0)
							<span class="badge badge-pending">Pending</span>
							@elseif($article->verified == 1)
								@if($article->published == 1)
								<span class="badge badge-publish">Published</span>
								@else
								<span class="badge badge-unpublish">Unpublished</span>
								@endif
							@else
							<span class="badge badge-rejected">Rejected</span>
							@endif
							<div class="article-headline">
								{{ $article->article_title }}
							</div>
							<hr>
							<div class="article-timestamp">
								Created on {{ date('d M Y', strtotime($article->created_at)) }}
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</section>
		<hr>
		{{-- <section>
			<h3>Saved Articles</h3>
		</section> --}}
	</div>

	<form id="edit_article_form" method="POST" action="{{ action('ArticleController@edit') }}">
		@csrf
		<input id="article_id" type="hidden" name="article_id" />
	</form>
@endsection

@section('scripts')
<script>
	$(function() {
		$('.card').on('click', function() {
			$('#article_id').val($(this).attr('data-id'));
			$('#edit_article_form').submit();
		});

		$('.article-delete').on('click', function(e) {
			e.preventDefault();
			swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				reverseButtons: true,
				showCancelButton: true,
				confirmButtonColor: '#f8bb86',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.value) {
				   var id = $(this).next().attr('data-id');
				   
				   document.location.href = '/articles/delete/' + id;
				}
			});
		});
	});
</script>
@endsection