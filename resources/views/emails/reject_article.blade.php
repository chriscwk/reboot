@extends('beautymail::templates.sunny')

@section('content')

    @include('beautymail::templates.sunny.contentStart')

        <p>Dear <b>{{ $name }}</b>,</p>
        <p style="text-align: justify;">While we think your article <b>"{{ $title }}"</b> was great but it was still lacking certain elements. We would love to see your article be published on <span style="color: #6600ff;">Reboot</span> and it's not impossible!</p>
       	<p>Just revise your article, add some juicy words, sprinkle a little magic, and re-send it to us for another round of reading!</p>
        <p style="text-align: justify;">We hope you will continue publishing articles and as the saying goes, <span style="font-style: italic;">"If at first you don't success, try, try again."</span></p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Reboot',
        	'link' => 'http://localhost:8000'
    ])

@stop