@extends('template')
@section('content')
    <main>
        @include('blocks.bread')
        <section class="section--content">
            <div class="container">
                <h1 class="page__subtitle text-center js-animate">{{ $h1 }}</h1>
                <br>
                <div class="text__page">{!! $text !!}</div>
            </div>
        </section>
    </main>
@stop
