@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <section class="section section--inner">
            <div class="section__container container text-content">
                <div class="section__date">Дата публикации: {{ $date }}</div>
                <h1>{{ $h1 }}</h1>
                {!! $text !!}
            </div>
        </section>
    </main>
@endsection