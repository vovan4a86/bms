@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section newses {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container">
                <h2 class="section__title section__title--inner">Новости</h2>
                <div class="newses__list">
                    @foreach($items as $item)
                        @include('news.list_item')
                    @endforeach
                </div>
                <div class="section__loader">
                    @include('paginations.default' ,['paginator' => $items])
                </div>
            </div>
        </section>
    </main>
@endsection