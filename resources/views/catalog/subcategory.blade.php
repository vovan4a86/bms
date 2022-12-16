@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="catalog section {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="catalog__container container">
                <h2 class="section__title">{{ $h1 }}</h2>
                <div class="catalog__text text-content">
                    {!! $category->announce !!}
                </div>
                @if(count($children))
                    <div class="catalog__links">
                        <nav class="subcategories">
                            <ul class="subcategories__list">
{{--                                <li class="subcategories__item">--}}
{{--                                    <a class="subcategories__link" href="#" title="Показать все">Показать все</a>--}}
{{--                                </li>--}}
                                @foreach($children as $child)
                                    <li class="subcategories__item">
                                        <a class="subcategories__link" href="{{ $child->url }}"
                                           title="{{ $child->name }}">{{ $child->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                        <div class="catalog__update">
                            <span>Цены обновлены 06.08.2022</span>
                        </div>
                    </div>
                @endif
                <!-- class=(catalogSubCatalogGrid ? 'catalog-list--grid' : '' )-->
                <div class="catalog-list catalog-list--grid">
                    <div class="catalog-list__top">
                        @include('catalog.views.togglers')
                    </div>
                    <div class="catalog-list__content">
                        <div class="catalog-list__head">
                            <div class="catalog-list__filter" data-filter-show>
                                <svg class="svg-sprite-icon icon-option">
                                    <use xlink:href="//static/images/sprite/symbol/sprite.svg#option"></use>
                                </svg>
                                <span>Показать фильтр</span>
                            </div>
                            <div class="catalog-list__hidden" data-filter-content>
                                <form action="#" id="cat-filter"></form>
                                <div class="catalog-list__grid catalog-list__grid--filters">
                                    <div class="catalog-list__column catalog-list__column--one">
                                        <div class="catalog-list__label">Фото</div>
                                    </div>
                                    <div class="catalog-list__column catalog-list__column--two">
                                        <div class="catalog-list__label">Наименование</div>
                                    </div>
                                    @if(count($sort))
                                        @foreach($sort as $name => $elems)
                                            <div class="catalog-list__column catalog-list__column--one catalog-list__column--filter">
                                                <!-- https://slimselectjs.com/options-->
                                                <!-- js--sources/modules/select.js-->
                                                <select class="catalog-select" name="{{ $name }}" multiple>
                                                        <option data-placeholder="true">{{ $filters[$loop->index]->name }}</option>
                                                    @foreach($elems = $elem)
                                                        <option value="{{ $elem }}">{{ $elem }}{{$filters[$loop->index]->measure}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="catalog-list__column catalog-list__column--two">
                                        <div class="catalog-list__label">Цена</div>
                                    </div>
                                </div>
                                </filter>
                            </div>
                        </div>
                        @if(count($products))
                            <div class="catalog-list__products">
                                @foreach($products as $item)
                                    @include('catalog.list_row')
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="catalog-list__footer">
                        <nav class="pagination">
                            {{ $products->links('catalog.page_nav') }}
                        </nav>
                        <div class="catalog-list__show">
                            <input type="hidden" name="curpage" id="curpage" value="{{ url()->full() }}">
                            <span>Показывать</span>
                            <select class="catalog-list__pages" name="pages" onchange="numberProducts(this)">
                                @foreach(Settings::get('prods_per_page') as $option)
                                    @if($loop->first)
                                        <option data-placeholder="true">10</option>
                                    @endif
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="section__text text-content">
                    {!! $category->text !!}
                </div>
            </div>
        </section>
    </main>
@endsection
