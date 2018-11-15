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
							<table id="publishers_table" class="table table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>Article Title</th>
										<th>Author</th>
										<th width="150px">Verification</th>
										<th width="150px">Status</th>
										<th>Created On</th>
										<th>Article Content</th>
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
													@if($article->verified == 0)
													<button class="btn btn-danger btn-sm btn-reject">Reject</button>
													<button class="btn btn-success btn-sm btn-approve m-l-10">Approve</button>
													@endif
												</td>
											</tr>
										@endforeach
									@else
										<tr><td colspan=7 style="text-align:center;">No data available.</td></tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script>
	$(function() {
		$('.btn-approve').on('click', function(e) {
		   var id = $(this).parents('tr').attr('data-id');
		   document.location.href = '/administrator/articles/approve/' + id;
		});

		$('.btn-reject').on('click', function(e) {
		   var id = $(this).parents('tr').attr('data-id');
		   document.location.href = '/administrator/articles/reject/' + id;
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
