@extends('template')
@section('content')
    @include('blocks.bread')
    <!-- headerIsWhite ? '' : 'page-head--dark'-->
    <div class="page-head {{ isset($headerIsBlack) ? 'page-head--dark' : null }}">
        <div class="page-head__container container">
            <div class="page-head__content">
                <div class="page-head__title">{{ $category->title }}</div>
                <div class="page-head__text">{!! $category->announce !!}</div>
            </div>
        </div>
    </div>
    <!-- class=productLayout && 'layout--product'-->
    <div class="layout">
        <div class="layout__container container">
            @include('catalog.blocks.aside')
            <div class="layout__content">
                <main>
                    <section class="s-subcatalog">
                        <div class="s-subcatalog__head">
                            <div class="s-subcatalog__search">
                                <form class="b-search" action="#">
                                    <input class="b-search__input" type="text" name="search-catalog"
                                           placeholder="Поиск по каталогу" autocomplete="off" required>
                                    <button class="b-search__button">
                                        <svg class="svg-sprite-icon icon-search">
                                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#search"></use>
                                        </svg>
                                        <span>Найти</span>
                                    </button>
                                </form>
                            </div>
                            <div class="s-subcatalog__update">
                                <div class="c-update">
                                    <div class="c-update__title">Каталог обновлён:</div>
                                    <div class="c-update__date">{{ $updatedDate }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="t-catalog">
                            @include('catalog.blocks.catalog_grid_head')
                            @each('catalog.product_item', $items, 'item')
                            @include('paginations.with_pages', ['paginator' => $items])
                        </div>
                        <div class="s-subcatalog__content text-block">
                            {!!  $category->text  !!}
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
    @include('blocks.callback_form')
@endsection
