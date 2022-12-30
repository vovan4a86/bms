@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        @include('blocks.page_head')
        <section class="services">
            <div class="services__container container">
                {!! $text  !!}
            </div>
        </section>
    </main>
@endsection
