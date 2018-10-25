@extends('layouts.admin_template')

@section('title', 'Create Category')

@section('content')

	<div class="main-panel">
		<div class="content-wrapper">
			<div class="page-header">
				<h3 class="page-title">Create Category</h3>
			</div>
			<form method="POST" action="{{ action('AdminController@category_store') }}" enctype="multipart/form-data">
				@csrf
				<div class="row m-b-20">
					<div class="col-xl-6">
						<div class="card">
							<div class="card-body">
								<div class="form-group">
									<label>Category Name</label>
									<input type="text" name="cat_name" class="form-control" placeholder="Category Name" required>
								</div>
								<div class="form-group">
									<label>Category Description</label>
									<textarea name="cat_desc" class="form-control" rows="5" spellcheck="false" required></textarea>
								</div>
								<div class="form-group">
									<label>Category Image</label>
									<input type="file" name="cat_img" class="file-upload-default">
									<div class="input-group col-xs-12">
				                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
				                        <span class="input-group-append">
				                          	<button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
				                        </span>
				                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-6">
						<button type="submit" class="btn btn-gradient-success">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>

@endsection

@section('scripts')
<script>
	$(function() {
		$('.file-upload-browse').on('click', function() {
	      	var file = $(this).parent().parent().parent().find('.file-upload-default');
	      	file.trigger('click');
	    });

	    $('.file-upload-default').on('change', function() {
	      	$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
	    });

	    @if(session('msg_status'))
			swal({
				html: '{!! session('msg_status') !!}',
				type: 'error',
				confirmButtonText: 'Ok'
			})
		@endif
	});
</script>
@endsection
