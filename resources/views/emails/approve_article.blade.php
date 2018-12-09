@extends('beautymail::templates.sunny')

@section('content')

    @include('beautymail::templates.sunny.contentStart')

        <p>Dear <b>{{ $name }}</b>,</p>
        <p style="text-align: justify;">Over here at <span style="color: #6600ff;">Reboot</span>, we really enjoyed the article titled <b>"{{ $title }}"</b> that you sent us! To show our gratitude, you'll be glad to know that we have decided to share this article on <span style="color: #6600ff;">Reboot</span> for the rest of the community to enjoy! <b>Congratulations!</b></p>
        <p style="text-align: justify;">Keep those juicy articles coming! Can't wait to see what's next in store for us. </p>

    @include('beautymail::templates.sunny.contentEnd')

    @include('beautymail::templates.sunny.button', [
        	'title' => 'Reboot',
        	'link' => 'http://localhost:8000'
    ])

@stop