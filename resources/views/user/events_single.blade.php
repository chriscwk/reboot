@extends('layouts.base_template')

@section('title', 'Event')

@section('links')
    <link rel="stylesheet" href="/user/css/single_article.css">
	<link rel="stylesheet" href="/user/css/meetup.css">
    <style>
		#event-map {
			border: 2px solid black;
		}
	</style>
@endsection

@section('content')

	<div class="main-container">
		<section>
			<div class="row">
                <div class="col-xl-12">
                    <div class="map-preview" id="event-map"></div>
                    <div class="singl-article-post">
                        <figure>
                            <div class="singl-article-title">
                                <h3 style="padding-bottom: 10px">
                                    {{ $event->event_title }}

                                    @if($alreadyRsvp)
                                    <a href="javascript:;" class="btn btn-secondary pull-right">Already RSVP</a>
                                    @elseif(count($attendees) < $event->event_max && \Auth::user()->id != $event->user_id && time() < strtotime($event->event_start_time))
                                    <a href="/events/rsvp/{{ $event->id }}?user_id={{ \Auth::user()->id }}" class="btn btn-info pull-right">RSVP</a>
                                    @elseif(count($attendees) == $event->event_max)
                                    <a href="javascript:;" class="btn btn-danger pull-right">Full</a>
                                    @elseif(time() >= strtotime($event->event_start_time))
                                    <a href="javascript:;" class="btn btn-danger pull-right">Overdue</a>
                                    @endif
                                </h3>
                            </div>
                            <hr>
                            <div class="event-attendees">
                                <p>Attendees ({{ count($attendees) }} / {{ $event->event_max }})</p>
                                @foreach($attendees AS $attendee)
                                <div class="attendee-item">
                                    <img class="attendee-img" src="http://icons-for-free.com/free-icons/png/512/1287507.png" />
                                    <p>{{ $attendee->username->first_name }} {{ $attendee->username->last_name }}</p>
                                </div>
                                @endforeach
                            </div>
                            <hr>
                            <div class="singl-article-status-bar">
                                <span>
                                    <i class="fa fa-user"></i>
                                    <a >{{ $event->event_organizer }}</a>
                                </span>
                                <span>
                                    <i class="fas fa-clock"></i>
                                    <a >{{ date('d M Y h:i A', strtotime($event->event_start_time)) }} to {{ date('d M Y h:i A', strtotime($event->event_end_time)) }}</a>
                                </span>
                                <span>
                            </div>
                            <div class="singl-article-details">
                                {!! $event->event_description !!}
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
		</section>
		<hr>
	</div>

@endsection

@section('scripts')
<script type='text/javascript' src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/build/ol.js"></script>
<script type='text/javascript' src='/user/js/ol-map-plot.js'></script>

<script>
	$(function() {
        destroyMap();

        if ('{{ $event->event_lat }}' != "" && '{{ $event->event_long }}' != "") {
            var lat = parseFloat('{{ $event->event_lat }}');
            var long = parseFloat('{{ $event->event_long }}');
            plot_map('event-map', lat, long);
        }
        else {
            $("#event-map").append('<div class="text-center"><label class="text-danger map-not-available">Map preview is not available</label></div>');
        }
	});
</script>
@endsection