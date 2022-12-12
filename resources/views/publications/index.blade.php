@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section publications {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container">
                <h2 class="section__title section__title--inner">Статьи</h2>
                @if(count($items))
                <div class="newses__list">
                    @foreach($items as $item)
                        @include('publications.list_item')
                    @endforeach
                </div>
                <div class="section__loader">
                    @include('paginations.default' ,['paginator' => $items])
                </div>
                @else
                    <div>
                      <h4>Нет статей</h4>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection