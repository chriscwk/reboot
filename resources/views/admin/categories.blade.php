@extends('layouts.admin_template')

@section('title', 'Categories')

@section('content')

	<div class="main-panel">
		<div class="content-wrapper">
			<div class="page-header">
				<h3 class="page-title">Categories</h3>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ action('AdminController@create_category_view') }}" class="btn btn-primary btn-icon-text"><i class="mdi mdi-plus btn-icon-prepend"></i>Add Category</a>
						</li>
					</ol>
				</nav>
			</div>

			@if(count($categories) > 0)
				<div class="row">
					@foreach($categories AS $category)
					<div class="col-xl-4">
						<button class="btn btn-danger btn-icon btn-rounded category-delete"><i class="mdi mdi mdi-delete"></i></button>
						<div data-id="{{ $category->id }}" class="card category-card m-b-20">
							<img class="category-img" src="/admins/categories/{{ $category->category_img }}" />
							<div class="card-body">
								<div class="category-title">{{ $category->category_name }}</div>
								<div class="category-desc">{{ $category->category_desc }}</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			@else
				<div class="empty-categories">
					It seems like you do not have any categories :(
					<br><br>
					<i class="mdi mdi-plus-circle icon-md"></i>
					<br>
					Add one now!
				</div>
			@endif
		</div>
	</div>

	<form id="edit_category_form" method="POST" action="{{ action('AdminController@edit_category_view') }}">
		@csrf
		<input id="cat_id_input" type="hidden" name="cat_id" />
	</form>
@endsection

@section('scripts')
<script>
	$(function() {
		$('.card').on('click', function() {
			$('#cat_id_input').val($(this).attr('data-id'));
			$('#edit_category_form').submit();
		});

		$('.category-delete').on('click', function(e) {
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
				   
				   document.location.href = '/admin/categories/delete/' + id;
				}
			});
		});

	    @if(session('msg_status'))
			swal({
				html: '{!! session('msg_status') !!}',
				type: '{{ session('msg_class') }}',
				confirmButtonText: 'Ok'
			});
		@endif
	});
</script>
@endsection
