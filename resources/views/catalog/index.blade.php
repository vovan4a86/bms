@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container">
                <div class="section__links">
                    <h2 class="section__title">Каталог продукциии</h2>
                    {{--
                    @if(Settings::get('catalog_price'))
                        <a class="btn btn--outlined btn--tag" href="{{ Settings::fileSrc(Settings::get('catalog_price')) }}" download="Прайс-лист Сталь-Сервис">
                            <svg class="svg-sprite-icon icon-tag">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#tag"></use>
                            </svg>
                            <span>Скачать прайс-лист</span>
                        </a>
                    @else
                        <span class="btn btn--outlined btn--tag">
                            <svg class="svg-sprite-icon icon-tag">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#tag"></use>
                            </svg>
                            <span>Прайс-лист не добавлен</span>
                        </span>
                    @endif
                    --}}
                </div>
                <ul class="products__list">
                    @foreach($categories as $item)
                        @php
                            $wide = in_array($loop->iteration, [1,2,3]);
                        @endphp
                                <!-- --wide-->
                        <li class="products__item {{ $wide ? 'products__item--wide' : '' }}">
                            <a class="products__link" href="{{ $item->url }}" title="{{ $item->name }}">
                                @if($item->image)
                                    <img class="products__picture lazy"
                                         data-src="{{ $wide ? $item->thumb(2) : $item->thumb(3) }}"
                                         src="{{ $wide ? $item->thumb(2) : $item->thumb(3) }}"
                                         alt="{{ $item->name }}" width="216" height="109">
                                @else
                                    <img class="products__picture lazy"
                                         data-src="/static/images/common/no_image.png"
                                         src="/static/images/common/no_image.png"
                                         alt="{{ $item->name }}" width="216" height="109">
                                @endif
                                <span class="products__row row-link">
                                    <span class="row-link__label">{{ $item->name }}</span>
                                    <span class="row-link__icon">
                                        <svg class="svg-sprite-icon icon-arrow">
                                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                                        </svg>
                                    </span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="section__text text-content">
                   {!! $text !!}
                </div>
            </div>
        </section>
    </main>

@endsection
