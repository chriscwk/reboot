@extends('layouts.admin_template')

@section('title', 'Articles')

@section('content')

	<div class="main-panel">
		<div class="content-wrapper">
			<div class="page-header">
				<h3 class="page-title">Articles</h3>
			</div>
			
			<div class="row">
				<div class="col-xl-12">
					<div class="card  m-b-20">
						<div class="card-body">
							<form id="list_articles_form" method="POST" action="{{ action('AdminController@approve_article') }}" enctype="multipart/form-data">
								@csrf
								<table id="publishers_table" class="table table-bordered" style="width:100%">
								<input type="hidden" name="selected_article_id" value="" />
								<input type="hidden" name="selected_category_id" value="" />
									<thead>
										<tr>
											<th>Article Title</th>
											<th>Author</th>
											<th width="120px">Verification</th>
											<th width="120px">Status</th>
											<th>Created On</th>
											<th>Article Content</th>
											<th width="210px">Category</th>
											<th width="195px">Options</th>
										</tr>
									</thead>
									<tbody>
										@if(count($articles) > 0)
											@foreach($articles AS $article)
												<tr data-id="{{ $article->id }}">
													<td>{{ $article->article_title }}</td>
													<td>{{ $article->author }}</td>
													<td>
														@if($article->verified == 0)
														<label class="badge badge-primary w-100">Pending</label>
														@elseif($article->verified == 1)
														<label class="badge badge-success w-100">Verified</label>
														@else
														<label class="badge badge-danger w-100">Rejected</label>
														@endif
													</td>
													<td>
														@if($article->published == 0)
														<label class="badge badge-secondary w-100">Unpublished</label>
														@else
														<label class="badge badge-success w-100">Published</label>
														@endif
													</td>
													<td>{{ date("d M Y", strtotime($article->created_at)) }}</td>
													<td><a href="{{ action('AdminController@article_content', $article->id) }}" target="_blank" class="btn btn-info btn-sm w-100">View</a></td>
													<td>
														<select class="form-control cat_selector" {{($article->verified != 0) ? "disabled" : ""}}>
														@foreach($categories AS $category)
															<option value="{{ $category->id }}" {{($category->id == $article->category_id) ? "selected" : ""}}>{{ $category->category_name }}</option>
														@endforeach
														@if ($article->temp_category_name != "")
															<option value="" selected>{{ $article->temp_category_name }}</option>
														@endif
														</select>
													</td>
													<td>
														<button class="btn btn-primary btn-sm btn-approve-category" style="{{($article->temp_category_name != '') ? '' : 'display: none;'}}">
															Approve Requested Category
														</button>
														<button class="btn btn-danger btn-sm btn-reject" style="{{($article->temp_category_name == '' && $article->verified == 0) ? '' : 'display: none;'}}">
															Reject
														</button>
														<button class="btn btn-success btn-sm btn-approve m-l-10" style="{{($article->temp_category_name == '' && $article->verified == 0) ? '' : 'display: none;'}}">
															Approve
														</button>
													</td>
												</tr>
											@endforeach
										@else
											<tr><td colspan=7 style="text-align:center;">No data available.</td></tr>
										@endif
									</tbody>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
		<form method="POST" action="{{ action('AdminController@category_store') }}" enctype="multipart/form-data">
			@csrf
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="categoryModalLabel">
							<strong>Create requested category</strong>
						</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<input type="hidden" name="is_cat_req" value="true" />
						<input type="hidden" name="article_id" value="" />
					</div>
					<div class="modal-body">
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
								<input type="checkbox" name="chk_set_approve" id="chk_set_approve">
								Set article to category and approve
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection

@section('scripts')
<script>
	$(function() {
		$('.btn-approve').on('click', function(e) {
			var currentRow = $(this).parents('tr');
			var id = currentRow.attr('data-id');
			var selected_category_id = currentRow.find("select").find(":selected").val();
			//document.location.href = '/administrator/articles/approve/' + id;
			$("input[name='selected_article_id']").val(id);
			$("input[name='selected_category_id']").val(selected_category_id);
			$("#list_articles_form").submit();
		});

		$('.btn-reject').on('click', function(e) {
			e.preventDefault();
			var id = $(this).parents('tr').attr('data-id');
			document.location.href = '/administrator/articles/reject/' + id;
		});

		$('.btn-approve-category').on('click', function(e) {
			e.preventDefault();
			var currentRow = $(this).parents('tr');
			var id = currentRow.attr('data-id');
			var user_defined_category_name = currentRow.find("select").find(":selected").text();

			$("input[name='cat_name']").val(user_defined_category_name);
			$("input[name='article_id']").val(id);
			$('#categoryModal').modal('toggle');
		});

		$('.cat_selector').on('change', function() {
			var currentRow = $(this).parents('tr');
			var id = currentRow.attr('data-id');
			var btnApproveCategory = currentRow.find("button.btn-approve-category");
			var btnApprove = currentRow.find("button.btn-approve");
			var btnReject = currentRow.find("button.btn-reject");

			if ($(this).val() != "") {
				btnApproveCategory.hide();
				btnApprove.show();
				btnReject.show();
			}
			else {
				btnApproveCategory.show();
				btnApprove.hide();
				btnReject.hide();
			}
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
