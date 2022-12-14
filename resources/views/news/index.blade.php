@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- headerIsWhite ? '' : 'page-head--dark'-->
        <div class="page-head {{ isset($headerIsBlack) ? 'page-head--dark' : null }}">
            <div class="page-head__container container">
                <div class="page-head__content">
                    <div class="page-head__title">{{ $h1 ?? $title }}</div>
                    <div class="page-head__text"></div>
                </div>
            </div>
        </div>
        <section class="newses">
            <div class="newses__container container">
                <div class="newses__grid">
                    @foreach($items as $item)
                        @include('news.list_item')
                    @endforeach
                </div>
                    @include('paginations.with_pages' ,['paginator' => $items])
                </div>
            </div>
        </section>
    </main>
@endsection