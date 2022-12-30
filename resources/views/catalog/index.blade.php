@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- headerIsWhite ? '' : 'page-head--dark'-->
        <div class="page-head">
            <div class="page-head__container container">
                <div class="page-head__content">
                    <div class="page-head__title">Каталог</div>
                    <div class="page-head__text">{!! $text !!}</div>
                </div>
            </div>
        </div>
        <section class="p-catalog" x-data="{ catalogView: 'grid' }">
            <div class="p-catalog__container container">
                <div class="p-catalog__controls">
                    <div class="p-catalog__search">
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
                    <div class="p-catalog__data">
                        <div class="p-catalog__update">
                            <div class="c-update">
                                <div class="c-update__title">Каталог обновлён:</div>
                                <div class="c-update__date">{{ $updated }}</div>
                            </div>
                        </div>
                        <div class="p-catalog__togglers">
                            <button class="p-catalog__toggle btn-reset" type="button"
                                    aria-label="Каталог, включить вид сеткой"
                                    :class="catalogView == 'grid' &amp;&amp; 'is-active'"
                                    @click="catalogView = 'grid'">
                                <svg class="svg-sprite-icon icon-dots">
                                    <use xlink:href="/static/images/sprite/symbol/sprite.svg#dots"></use>
                                </svg>
                            </button>
                            <button class="p-catalog__toggle btn-reset" type="button"
                                    aria-label="Каталог, включить вид списком"
                                    :class="catalogView == 'list' &amp;&amp; 'is-active'"
                                    @click="catalogView = 'list'">
                                <svg class="svg-sprite-icon icon-list">
                                    <use xlink:href="/static/images/sprite/symbol/sprite.svg#list"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-catalog__views">
                    <div class="p-catalog__grid-view" x-show="catalogView == 'grid'" x-transition.opacity x-cloak>
                        @foreach($categories as $item)
                            <div class="p-catalog__card">
                                <a class="card-link" href="{{ $item->url }}" title="{{ $item->name }}">
                                    <span class="card-link__count">
                                        {{ $item->getRecurseProductsCount() }}
                                        {{ SiteHelper::getNumEnding($item->getRecurseProductsCount(), ['товар', 'товара', 'товаров']) }}
                                    </span>
                                    <span class="card-link__title">{{ $item->name }}</span>
                                    <img class="card-link__pic lazy" src="/"
                                         data-src="{{ $item->getImageUrl() }}"
                                         width="305" height="280" alt=""/>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-catalog__list-view" x-show="catalogView == 'list'" x-transition.opacity x-cloak>
                        <ul class="p-catalog__list list-reset">
                            @foreach($categories as $item)
                                <li class="p-catalog__item">
                                    <a class="p-catalog__link" href="{{ $item->url }}">{{ $item->name }}</a>
                                    @if($children = $item->public_children)
                                        <ul class="p-catalog__sublist list-reset">
                                            @foreach($children as $item)
                                                <li class="p-catalog__subitem">
                                                    <a class="p-catalog__sublink" href="{{ $item->url }}">{{ $item->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        @include('blocks.callback_form')
        @include('catalog.blocks.payments')
    </main>
@endsection
