@extends('layouts.base_template')

@section('title', 'My Meetups')

@section('links')
	<link rel="stylesheet" href="/user/css/meetup.css">

	<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/css/ol.css" type="text/css">
	<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/build/ol.js"></script>
@endsection

@section('content')

	<div class="main-container">
		<section id="event-container">
			<div class="section-header">
				<h3>My Meetups</h3>
				<a href="{{ action('EventController@create') }}" class="btn btn-info btn-sm"><i class="fas fa-plus"></i></a>
			</div>

		</section>
		<hr>
	</div>

	<form id="edit_event_form" method="POST" action="{{ action('EventController@edit') }}">
		@csrf
		<input id="event_id" type="hidden" name="event_id" />
	</form>
@endsection

@section('scripts')
<script type='text/javascript' src='/user/js/ol-map-plot.js'></script>

<script>
	$(document).ready(function () {
		retrieve_my_events();

		$(document).on('click', '.card', function () {
			window.location.href = $(this).attr('data-url');
		});

		$(document).on('click', '.meetup-delete', function (e) {
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
				   document.location.href = '/events/delete/' + id;
				}
			});
		});

		function retrieve_my_events() {
			$.ajax({
				headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
				type: 'POST',
				url: '/events/getEvents',
				data: {'creator': '{{ \Auth::user()->id }}'},
				success: function(data) {
					data = '{event:' + JSON.stringify(data) + '}';
					data = eval('(' + data + ')');
					$.get('/user/template/tpEventCard.htm', function (template) {
						jQuery(template).tmpl(data).appendTo('#event-container');

						var dataArray = data.event;
						for (var i = 0; i < dataArray.length; i++){
							var mapId = "event-map-" + (i + 1);
							var lat = parseFloat(dataArray[i].event_lat);
							var long = parseFloat(dataArray[i].event_long);
							plot_map(mapId, lat, long);
						};
					});
					
				},
				error: function(data) {
					$.ajax(this);
					return;
				}
			});
		}

	});
</script>
@endsection