@extends('template')
@section('content')
    @include('blocks.bread')
    <title>{{ $title }}</title>
    <main>
        <section class="search-page">
            <div class="search-page__container container">
                <div class="search-page__title">
                    Результат поиска&nbsp;
                    <span>«{{ $query }}»</span>
                </div>
                @if(count($items))
                    <div class="search-page__output">
                        <div class="t-catalog">
                            @include('search.search_grid_head')
                            @foreach($items as $item)
                                @include('search.search_product_item', compact($item))
                            @endforeach
                        </div>
                    </div>
                    @include('paginations.with_pages', ['paginator' => $items])
                @else
                    <h4>По вашему запросу ничего не найдено</h4>
                @endif
            </div>
        </section>
    </main>
@endsection
