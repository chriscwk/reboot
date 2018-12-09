@extends('layouts.admin_template')

@section('title', 'Publishers')

@section('content')

	<div class="main-panel">
		<div class="content-wrapper">
			<div class="page-header">
				<h3 class="page-title">Publishers</h3>
			</div>
			
			<div class="row">
				<div class="col-xl-12">
					<div class="card  m-b-20">
						<div class="card-body">
							<table id="publishers_table" class="table table-bordered" style="width:100%">
								<thead>
									<tr>
										<th>Full Name</th>
										<th width="150px">Status</th>
										<th>Joined On</th>
										<th width="195px">Options</th>
									</tr>
								</thead>
								<tbody>
									@if(count($users) > 0)
										@foreach($users AS $user)
											<tr data-id="{{ $user->id }}">
												<td>{{ $user->first_name }} {{ $user->last_name }}</td>
												<td>
													@if($user->pending_status == 0)
													<label class="badge badge-primary w-100">Pending</label>
													@elseif($user->pending_status == 1)
													<label class="badge badge-success w-100">Approved</label>
													@else
													<label class="badge badge-danger w-100">Rejected</label>
													@endif
												</td>
												<td>{{ date("d M Y", strtotime($user->created_at)) }}</td>
												<td>
													@if($user->pending_status == 0)
													<button class="btn btn-danger btn-sm btn-reject">Reject</button>
													<button class="btn btn-success btn-sm btn-approve m-l-10">Approve</button>
													@endif
												</td>
											</tr>
										@endforeach
									@else
										<tr><td colspan=3 style="text-align:center;">No data available.</td></tr>
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
		   document.location.href = '/admin/users/publishers/approve/' + id;
		});

		$('.btn-reject').on('click', function(e) {
		   var id = $(this).parents('tr').attr('data-id');
		   document.location.href = '/admin/users/publishers/reject/' + id;
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
