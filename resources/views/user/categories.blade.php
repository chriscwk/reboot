@extends('layouts.base_template')

@section('title', 'Categories')

@section('links')
	<link rel="stylesheet" href="/user/css/article.css">
@endsection

@section('content')

	<div class="main-container">
        <section>
            <div class="section-header">
				<h3>Categories</h3>
			</div>

            <div class="row">
				@foreach($categorisedArticles AS $category)
				<div class="col-xl-4">
					<div data-id="{{ $category->id }}" class="card article-card">
						<img class="category-img" src="/admin/categories/{{ $category->category_img }}" />
						<div class="card-body">
							<div class="article-headline">
								{{ $category->category_name }}
								<span class="badge badge-publish category-badge">{{ $category->articles_count }}</span>
							</div>
							<hr>
							<div class="article-timestamp">
								{{ $category->category_desc }}
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>

        </section>
	</div>

@endsection

@section('scripts')
<script>
	$(function() {
		$('.card').on('click', function() {
			window.location.href = '/categories/' + $(this).attr('data-id');
		});
	});
</script>
@endsection