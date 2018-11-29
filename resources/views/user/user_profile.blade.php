@extends('layouts.base_template')

@section('title', 'User Profile')

@section('links')
	<link rel="stylesheet" href="/user/css/user_profile.css">
	<link rel="stylesheet" href="/user/css/article.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
@endsection

@section('content')

	<form id="user_profile_form" method="POST" action="{{ action('UserController@update') }}" enctype="multipart/form-data">
		@csrf
		<div class="main-container">
			<div id="divProfileDetails">
				<h3>My Profile Details</h3>
				<hr>
				<div class="row">
					<input type="hidden" name="user_id" value="{{ $userProfile->id }}" />
					<div class="col-xl-8">
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label>First Name</label>
									<input id="user_first_name" type="text" class="form-control required only-text" name="user_first_name" value="{{ $userProfile->first_name }}">
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label>Last Name</label>
									<input id="user_last_name" type="text" class="form-control required only-text" name="user_last_name" value="{{ $userProfile->last_name }}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-6">
								<div class="form-group">
									<label>Contact Number</label>
									<input id="user_contact_number" type="text" class="form-control required" name="user_contact_number" value="{{ $userProfile->phone_number }}">
								</div>
							</div>
							<div class="col-xl-6">
								<div class="form-group">
									<label>Birth Date</label>
									<input id="user_birth_date" type="text" class="form-control required" name="user_birth_date" value="{{ $userProfile->birth_date }}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-12">
								<div class="form-group">
									<label>Email</label>
									<input id="user_email" type="text" class="form-control" name="user_email" readonly value="{{ $userProfile->email }}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-3">
								<div class="form-group">
									<button type="button" class="btn btn-info btn-block btn-submit">Save</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4">
						<div class="row">
							<div class="col-xl-12">
								<table class="table">
									<tr>
										<td>Status</td>
										<td class="text-center">
											@if($userProfile->publisher == 0)
											<h3 class="text-danger">Non Publisher</h3>
											@elseif($userProfile->publisher == 1)
											<h3 class="text-success">Publisher</h3>
											@endif
										</td>
									</tr>
									<tr>
										<td>Published Articles</td>
										<td class="text-center">{{ $userProfile->published_articles }}</td>
									</tr>
									<tr>
										<td>Organized Meetups</td>
										<td class="text-center">{{ $userProfile->organized_meetups }}</td>
									</tr>
									<tr>
										<td>Joined Date</td>
										<td class="text-center">{{ date('d F Y', strtotime($userProfile->created_at)) }}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<br>

			<div id="divFavoritedArticles">
				<h3>My Favorite Articles</h3>
				<hr>
				<div class="row">
					@foreach ($favoritedArticles as $article)
					<div class="col-xl-12">
						<div data-id="{{ $article->id }}" data-url="/articles/{{ $article->id }}/{{ $article->encoded_name }}" class="card article-card">
							<img class="article-img" src="/user/articles/{{ $article->article_img }}" />
							<div class="card-body">
								<div class="article-headline">
									{{ $article->article_title }}
								</div>
								<hr>
								<div class="article-timestamp">
									Created on {{ date('d M Y', strtotime($article->created_at)) }}
									<div class="article-author">By <strong>{{ $article->author }}</strong></div>
								</div>
							</div>
						</div>
					</div>
					@endforeach
					<div class="col-xl-12">
					{{ $favoritedArticles->links() }}
					</div>
				</div>
			</div>
		</div>

	</form>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>

<script>
	$(document).ready(function () {
		$('input[name="user_contact_number"]').mask('(+6) 000-00000000');
		$('input[name="user_birth_date"]').on('keydown', function(){
			return false;
		});
		$('input[name="user_birth_date"]').datepicker({
            format: 'dd/mm/yyyy'
        });
		$( ".only-text" ).keypress(function(e) {
			var key = e.keyCode;
			if ((key > 64 && key < 91) || (key > 96 && key < 123)) {
				
			}
			else {
				return false;
			}
		});

		$(document).on('click', '.card', function () {
            window.location.href = $(this).attr('data-url');
        });

		$('.btn-submit').on('click', function() {
			var errorLess = true;
			$(".required").each(function () {
				if ($(this).is("input")) {
					if ($(this).val() == "") {
						var label = $(this).prev().text();
						errorLess = false;
						swal({
							text: label + " can't be empty.",
							type: "error",
							confirmButtonText: "Ok"
						});
						return errorLess;
					}
				}
			});

			if (errorLess) {
				$('#user_profile_form').submit();
			}

		});
	});
</script>
@endsection
