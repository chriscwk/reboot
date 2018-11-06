@extends('layouts.base_template')

@section('title', 'Create Meetup')

@section('links')
	<link rel="stylesheet" href="/user/css/meetup.css">
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.5/css/froala_editor.min.css' rel='stylesheet' type='text/css' />
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.5/css/froala_style.min.css' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="/user/plugins/froala/css/colors.min.css">
	<link rel="stylesheet" href="/user/plugins/froala/css/table.min.css">
    <link rel="stylesheet" href="/user/plugins/froala/css/code_view.min.css">
    
	<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/css/ol.css" type="text/css">

	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
	
<form id="event_form" method="POST" action="{{ action('EventController@store') }}" enctype="multipart/form-data">
	@csrf
	<div class="main-container">
		<h3>Organize a Meetup</h3>
		<hr>
		<p class="disclaimer">
			Create a meetup or event you would like people to join. This aims to serve the sole purpose of allowing a get-along and sharing of information together amongst developers with the same interests. Therefore, be specific in your title and description!
		</p>
        <hr>
		<div class="row">
			<div class="col-xl-12">
				<div class="map-preview" id="event-map">
                    <div class="text-center"><label id="map-not-available" class="text-danger">LOCATION NOT FOUND</label></div>
                    <div class="text-center custom-spinner align-middle">
                        <i class="fa fa-spin fa-spinner fa-10x"></i>
                        <div class="custom-spinner-text">loading...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="form-group">
                    <label>Location</label>
                    <input id="event_loca_address" type="text" class="form-control required" name="event_loca_address" placeholder="Enter Address">
                </div>
            </div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xl-12">
				<div class="form-group">
				    <label>Event Title</label>
				    <input id="event_title" type="text" class="form-control required" name="event_title" placeholder="Enter Event Title">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-6">
                <div class="form-group">
				    <label>Max Attendees</label>
				    <input id="event_max" type="text" class="form-control only-number required" name="event_max" placeholder="Enter Max Attendees">
				</div>
			</div>
			<div class="col-xl-6">
				<div class="form-group">
					<label>Event Time</label>
					<input id="event_time" type="text" name="event_time" class="form-control default-date-picker required" >	
				</div>
			</div>
        </div>
		<hr>
		<h4 class="text-center m-b-30">Short description of your meetup!</h4>
		<div class="row">
			<div class="col-xl-12 m-b-30">
				<textarea name="event_description" id="event_description" class="required"></textarea>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xl-4 offset-xl-4">
				<button type="button" class="btn btn-info btn-block btn-submit">Create Event</button>
			</div>
		</div>
	</div>
</form>

@endsection

@section('scripts')
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.8.5/js/froala_editor.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/align.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/colors.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/font_size.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/table.min.js'></script>
<script type='text/javascript' src='/user/plugins/froala/js/code_view.min.js'></script>

<script type='text/javascript' src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.2.0/build/ol.js"></script>
<script type='text/javascript' src='/user/js/ol-map-plot.js'></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
	$(function() {

		$('input[name="event_time"]').on('keydown', function(){
			return false;
		});

		$('input[name="event_time"]').daterangepicker({
			timePicker: true,
			locale: { format: 'DD/MM/YYYY HH:mm' }
		});
		$('input[name="event_time"]').val("");
  
		$(".only-number").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

        $(".custom-spinner").hide();
		$('#event_loca_address').focusout(function () {
            if ($(this).val() != "") {
                $("#map-not-available").hide();
                $(".custom-spinner").show();
                destroyMap();

                $.ajax({
                    headers: { 'X-CSRF-TOKEN' : "{{ csrf_token() }}" },
                    type: 'POST',
                    url: '/events/getLatLong',
                    data: { 'address' : $(this).val() },
                    success: function(data) {
                        if (data == "")
                        {
                            $("#map-not-available").show();
                            $(".custom-spinner").hide();
                        }
                        else
                        {
                            var split = data.split(',');
                            plot_map('event-map', parseFloat(split[0]), parseFloat(split[1]));
                            
                            $("#map-not-available").hide();
                            $(".custom-spinner").hide();
                        }
                    },
                    error: function(data) {
                        $.ajax(this);
                        return;
                    }
			    });
            }
            else {
                $("#map-not-available").show();
                destroyMap();
            }
        });

		$('#event_description').froalaEditor({
			toolbarInline: false, 
			heightMin: 500
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
				else if ($(this).is("textarea")) {
					if ($(this).val() == "") {
						var label = "Event description";
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
				$('#event_form').submit();
			}

		});

	});
</script>
@endsection