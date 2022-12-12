@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <section class="section section--inner">
            <div class="section__container container text-content">
                <h2 class="section__title section__title--inner">{{ $h1 }}</h2>
                {!! $text !!}
            </div>
        </section>
    </main>
@endsection
