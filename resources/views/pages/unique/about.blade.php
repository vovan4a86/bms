@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        @include('blocks.about')
        @include('blocks.masonry')
        @include('blocks.geography')
        @include('blocks.question')
    </main>

@stop