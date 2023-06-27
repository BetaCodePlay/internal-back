@extends('back.email')

@section('title')
    {{ $title }}
@endsection

@section('subtitle')
    {{  $subtitle }}
@endsection

@section('content')
    {{ $content }}
@endsection

@section('footer')
    <strong>
        {{ $footer }}
    </strong>
@endsection
