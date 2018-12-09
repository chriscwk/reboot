@extends('beautymail::templates.sunny')

@section('content')

    @include('beautymail::templates.sunny.contentStart')

        <p>Dear <b>{{ $name }}</b>,</p>
        <p style="text-align: justify;">Unfortunately, you have not been granted <span style="color: #6600ff;">publisher</span> status by our admins :(</p>
        <p style="text-align: justify;">We hope that you still enjoy what <span style="color: #6600ff;">Reboot</span> platform has to offer as well as the articles published by other users!</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Reboot',
        	'link' => 'http://localhost:8000'
    ])

@stop