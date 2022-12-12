@extends('template')
@section('content')
    @include('blocks.bread')
    <!-- homepage ? '' : 'section--inner'-->
    <section class="section search-page {{ Request::url() === '/' ? '' : 'section--inner' }}">
        <div class="container">
            <h2 class="section__title section__title--inner">{{ $title }}</h2>
            @if(count($items))
                <div class="search-page__list">
                    @foreach($items as $item)
                      @include('search.search_item', compact($item))
                    @endforeach
                </div>
                <div class="section__loader">
                    @include('search.ajax_pagination' ,['paginator' => $items])
                </div>
            @else
                <h4>По вашему запросу ничего не найдено</h4>
            @endif
        </div>
    </section>
    </main>
@endsection