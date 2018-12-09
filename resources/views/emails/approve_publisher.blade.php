@extends('beautymail::templates.sunny')

@section('content')

    @include('beautymail::templates.sunny.contentStart')

        <p>Dear <b>{{ $name }}</b>,</p>
        <p style="text-align: justify;">You have been granted <span style="color: #6600ff;">publisher</span> status by one our admins! <b>Congratulations!</b></p>
        <p style="text-align: justify;">You may now begin sharing articles with fellow users of <span style="color: #6600ff;">Reboot</span> platform and even create your own articles! We can't wait to see what you have in store for us.</p>
        <p>Enjoy publishing!</p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Reboot',
        	'link' => 'http://localhost:8000'
    ])

@stop