@extends('layouts.base_template')

@section('title', 'Meetups')

@section('links')
	<link rel="stylesheet" href="/user/css/meetup.css">

	<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/css/ol.css" type="text/css">
	<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/build/ol.js"></script>
@endsection

@section('content')

	<div class="main-container">
		<section id="event-container">
			<div class="section-header">
				<h3>Meetups</h3>
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
		retrieve_events();

		$(document).on('click', '.card', function () {
			window.location.href = $(this).attr('data-url');
		});

		function retrieve_events() {
			$.ajax({
				headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
				type: 'POST',
				url: '/events/getEvents',
				success: function(data) {
					data = '{event:' + JSON.stringify(data) + '}';
					data = eval('(' + data + ')');
					$.get('/user/template/tpEventAllCard.htm', function (template) {
						jQuery(template).tmpl(data).appendTo('#event-container');

						var dataArray = data.event;
						for (var i = 0; i < dataArray.length; i++){
							var mapId = "event-map-" + (i + 1);
							if (dataArray[i].event_lat != "" && dataArray[i].event_long != "") {
								var lat = parseFloat(dataArray[i].event_lat);
								var long = parseFloat(dataArray[i].event_long);
								plot_map(mapId, lat, long);
							}
							else {
								$("#" + mapId).append('<div class="text-center"><label class="text-danger map-not-available">Map preview is not available</label></div>');
							}
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