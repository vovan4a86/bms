@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section sitemap {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container">
                <h2 class="section__title section__title--inner">Карта сайта</h2>
                  <ul class="sitemap__list">
                    <li>
                        <a href="{{ route('main') }}">Главная</a>
                        <ul class="sitemap__middle {{ Settings::get('sitemap_view') == 2 ? 'sitemap__middle--grid' : ''}}">
                            @foreach($sitemap as $item)
                                {{-- исключаем страницу Поиск в каталоге --}}
                                @if($item->id == 28)
                                    @continue
                                @endif
                                {{-- разделы каталога --}}
                                @if($item->id == 2)
                                    <li>
                                        <a href="{{ $item->url }}">Каталог продукции</a>
                                        <ul class="sitemap__small">
                                            @foreach($catalog as $cat)
                                                <li>
                                                    <a href="{{ $cat->url }}">{{ $cat->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                <li>
                                    <a href="{{ $item->url }}">{{ $item->name }}</a>
                                    @if($children = $item->getPublicChildren())
                                        <ul class="sitemap__small">
                                            @foreach($children as $child)
                                                <li>
                                                    <a href="{{ $child->url }}">{{ $child->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </section>
    </main>
@stop
