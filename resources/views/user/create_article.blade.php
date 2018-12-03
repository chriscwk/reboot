@extends('layouts.base_template')

@section('title', 'Create Article')

@section('links')
	<link rel="stylesheet" href="/user/css/article.css">
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.5/css/froala_editor.min.css' rel='stylesheet' type='text/css' />
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.5/css/froala_style.min.css' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="/user/plugins/froala/css/colors.min.css">
	<link rel="stylesheet" href="/user/plugins/froala/css/table.min.css">
	<link rel="stylesheet" href="/user/plugins/froala/css/code_view.min.css">

	<style>
		#content-previewer{
			border: 2px solid black;
		}
		.category-table {
			width: 100%;
		}
	</style>
@endsection

@section('content')
	
<form id="article_form" method="POST" action="{{ action('ArticleController@store') }}" enctype="multipart/form-data">
	@csrf
	<div class="main-container">
		<h3>Create an Article</h3>
		<hr>
		<p class="disclaimer">
			Articles submitted by you will go through a verification process by our admins and will be published automatically once verified. You may manually unpublish and publish, make edits to your article (will go through verification again before edited content is shown), or even delete your article once your article has been verified.
		</p>
		<hr>
		<div class="row">
			<div class="col-xl-12">
				<img class="preview-img" src="/user/articles/{{ $sample_image }}" />
			</div>
			<label for="article_img" class="btn-article-img">Upload Cover Image</label>
    		<input type="file" class="form-control-file" id="article_img" name="article_img" style="display:none;">
		</div>
		<hr>
		<div class="row">
			<div class="col-xl-6">
				<div class="form-group">
				    <label>Headline</label>
				    <input id="headline" type="text" class="form-control" name="article_headline" placeholder="Enter Headline">
				</div>
			</div>
			<div class="col-xl-6">
				<div class="form-group">
					<table class="category-table">
						<tr>
							<td colspan=2><label>Category</label></td>
						</tr>
						<tr>
							<td><input type="radio" name="rb_category" value="predefined_cat" class="rb_category" checked></td>
							<td>
								<select class="form-control" name="cat_id" id="article_category">
									@foreach($categories AS $category)
									<option value="{{ $category->id }}">{{ $category->category_name }}</option>
									@endforeach
								</select>
							</td>
						</tr>
						<tr>
							<td colspan=2><hr></td>
						</tr>
						<tr>
							<td colspan=2><label>Don't see the category you want?</label></td>
						</tr>
						<tr>
							<td><input type="radio" name="rb_category" value="other_cat" class="rb_category"></td>
							<td>
								<input id="other_category" type="text" class="form-control" name="other_category" placeholder="Enter Your Desired Category" readonly>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<hr>
		<h4 class="text-center">Share a link</h4>
		<div class="row">
			<div class="col-xl-6 offset-xl-3">
				<label>Article Link</label>
				<div class="input-group mb-3">
					<input id="articleLink" type="text" class="form-control" placeholder="Enter Link" name="article_link">
					<div class="input-group-append">
						<button type="button" class="btn btn-success fetch-link-preview"><i class="fa fa-check"></i></button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-12">
				<label>Link Preview</label>
				<div id="content-previewer">
					<div class="text-center custom-spinner" style="display: none;">
						<i class="fa fa-spin fa-spinner fa-10x"></i>
						<div class="custom-spinner-text">loading...</div>
					</div>
				</div>
			</div>
		</div>
		<h6 class="text-center m-t-40">OR</h6>
		<hr>
		<h4 class="text-center m-b-30">Write your own article!</h4>
		<div class="row">
			<div class="col-xl-12 m-b-30">
				<textarea name="article_html" id="articleHtml"></textarea>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xl-4 offset-xl-4">
				<button type="button" class="btn btn-info btn-block btn-submit">Save Article</button>
			</div>
		</div>
	</div>
	<input type="hidden" name="sample_image" value="{{ $sample_image }}" />
</form>

@endsection

@section('scripts')
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.5/js/froala_editor.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/align.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/colors.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/font_size.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/table.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/code_view.min.js'></script>

<script>
	$(function() {

		$( 'input[name="rb_category"]' ).on( "click", function() {
			if ($( 'input[name="rb_category"]:checked' ).val() == "predefined_cat") {
				$("#article_category").removeAttr('disabled');
				$("#other_category").prop('readonly', 'true');
				$("#other_category").val("");
			}
			else {
				$("#article_category").prop('disabled', 'disabled');
				$("#other_category").removeAttr('readonly');
			}
		});

		$('.fetch-link-preview').on('click', function (e) {
			var site_url = $('#articleLink').val();
			$(".custom-spinner").show();
			$("#content-previewer > img").remove();

			$.ajax({
				headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
				type: 'POST',
				url: '/articles/crawl_site',
				data: { 'site_url' : site_url },
				success: function(data) {
					$(".custom-spinner").hide();
					$("#content-previewer").append(data);
				},
				error: function(data) {
					$.ajax(this);
                	return;
				}
			});
		});

		$('#articleHtml').froalaEditor({
			toolbarInline: false, 
			heightMin: 500
		});

		$('.btn-submit').on('click', function() {
			var headline = $('#headline').val().trim();
			var link 	 = $('#articleLink').val().trim();
			var txtArea  = $('#articleHtml').val();
			var otherCat = $('#other_category').val();

			if(headline == '' || headline == null) {
				swal({
					text: "Headline can't be empty.",
					type: "error",
					confirmButtonText: "Ok"
				});
			} else if(link == '' && txtArea == '') {
				swal({
					text: "You need to include a link to an article or write your own article.",
					type: "error",
					confirmButtonText: "Ok"
				});
			} else if(link != '' && txtArea != '') {
				swal({
					text: "You may only choose between sharing a link to an article or creating your own article.",
					type: "error",
					confirmButtonText: "Ok"
				});
			} else if ($( 'input[name="rb_category"]:checked' ).val() == "other_cat" && otherCat == "") {
				swal({
					text: "As you have selected the Others category, please ensure you have put in your desired category.",
					type: "error",
					confirmButtonText: "Ok"
				});
			} else {
				$('#article_form').submit();
			}
		});

		$("#article_img").change(function() {
		  readURL(this);
		});

		function readURL(input) {
		  	if (input.files && input.files[0]) {
			    var reader = new FileReader();

			    reader.onload = function(e) {
			      	$('.preview-img').attr('src', e.target.result);
			    }

			    reader.readAsDataURL(input.files[0]);
			}
		}
	});
</script>
@endsection