@extends('beautymail::templates.sunny')

@section('content')

    @include('beautymail::templates.sunny.contentStart')

        <p>Dear <b>{{ $name }}</b>,</p>
        <p style="text-align: justify;">Below is the link to reset your password.</p>

        <a href="http://localhost:8000/password/reset/{{ $id }}">Reset my password</a>

    @include('beautymail::templates.sunny.contentEnd')

@stop