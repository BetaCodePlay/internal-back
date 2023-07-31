@extends('back.validate-email')

@section('title')
    {{ $title }}
@endsection

@section('subtitle')
    {{  $subtitle }}
@endsection

@section('content')
    {{ $content }}
@endsection

@section('button')
    <a href="{{ $url }}"
       style="color: #fff; font-family: Arial, Helvetica, sans-serif; font-size: 13px; background: #ff8500; padding:  15px 40px; text-transform: uppercase; font-weight: bold; text-decoration: none; border-radius: 5px;">
        {{ $button }}
    </a>
@endsection

@section('footer')
    <strong>
        {{ $footer }}
    </strong>
    <br>
    <br>
    <a href="{{ $url }}" style="text-decoration: none; color: #ff8500; font-weight: 600;">
        {{ $url }}
    </a>
    <br>
    <br>
@endsection
