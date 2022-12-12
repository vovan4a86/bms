@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section reviews {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container">
                <h2 class="section__title section__title--inner">Отзывы клиентов</h2>
                {!! $text !!}
            </div>
        </section>
    </main>
@endsection