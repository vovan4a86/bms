@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="product__container container">
                <div class="section__links">
                    <h2 class="section__title">{{ $product->name }}</h2>
                    {{--
                    @if(Settings::get('catalog_price'))
                        <a class="btn btn--outlined btn--tag" href="#" download="Прайс-лист Сталь-Сервис">
                            <svg class="svg-sprite-icon icon-tag">
                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#tag"></use>
                            </svg>
                            <span>Скачать прайс-лист</span>
                        </a>
                    @else
                        <span class="btn btn--outlined btn--tag">
                        <svg class="svg-sprite-icon icon-tag">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#tag"></use>
                        </svg>
                        <span>Прайс-лист не добавлен</span>
                    </span>
                    @endif
                    --}}
                </div>
                <div class="product__content">
                    <div class="product__view">
                        <div class="slider-product swiper" data-product-slider>
                            <div class="slider-product__nav slider-nav">
                                <div class="slider-product__icon slider-product__icon--prev slider-nav__icon slider-nav__icon--white slider-nav__prev"
                                     data-product-prev>
                                    <svg class="svg-sprite-icon icon-arrow-prev">
                                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow-prev"></use>
                                    </svg>
                                </div>
                                <div class="slider-product__icon slider-product__icon--next slider-nav__icon slider-nav__icon--white slider-nav__next"
                                     data-product-next>
                                    <svg class="svg-sprite-icon icon-arrow">
                                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="slider-product__wrapper swiper-wrapper">
                                @if(count($images))
                                    @foreach($images as $image)
                                        <div class="slider-product__slide swiper-slide">
                                            <a class="slider-product__link"
                                               href="{{ $image->thumb(3) }}"
                                               data-fancybox="gallery">
                                                <img class="slider-product__picture swiper-lazy" src="/"
                                                     data-src="{{ $image->thumb(3) }}"
                                                     alt="alt" width="586" height="386">
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="slider-product__slide swiper-slide">
                                        <a class="slider-product__link"
                                           href="{{ $product->getRootImage() }}"
                                           data-fancybox="gallery">
                                            <img class="slider-product__picture swiper-lazy" src="/"
                                                 data-src="{{ $product->getRootImage() }}"
                                                 alt="alt" width="586" height="386">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="product__info" id="product-order">
                        <div class="product-data">
                            <div class="product-data__price">
                                @if($product->price)
                                    Цена {{ number_format($product->multiplyPrice($product->price), 2, ',', ' ') }} руб.
                                / {{ $product->measure }}
                                @else
                                    Цена по запросу
                                @endif
                            </div>
                            <div class="product-data__fields">
                                <input type="hidden" name="price" value="{{ $product->multiplyPrice($product->price) }}" data-popup-price>
                                <input type="hidden" name="factor" value="{{ $product->factor }}" data-popup-factor>
                                <input type="hidden" name="measure" value="{{ $product->measure }}" data-popup-measure>
                                <input type="hidden" name="hweight" value="{{ $count_weight }}" data-popup-weight>
                                <input type="hidden" name="factor_weight" value="{{ $factor_m2_weight }}" data-popup-factorweight>
                                <div class="fields">
                                    <div class="fields__field">
                                        <label class="fields__label">Длина, м
                                            <input class="fields__input" type="number" name="length"
                                                   value="{{ $product->getLength() }}" disabled required data-popup-length>
                                        </label>
                                    </div>
                                    <div class="fields__field">
                                        <label class="fields__label">Кол-во, шт
                                            <input class="fields__input" type="number" name="count"
                                                   value="{{ $count_per_tonn }}" required
                                                   data-popup-count>
                                        </label>
                                    </div>
                                    <div class="fields__field">
                                        <label class="fields__label">Кол-во, {{ $product->measure }}
                                            <input class="fields__input" type="number" name="weight"
                                                   step="0.1" value="{{ round($count_weight, 3) }}" required
                                                   data-popup-weight>
                                        </label>
                                    </div>
                                    <div class="fields__value">
                                        <label class="fields__label">На сумму, ₽
                                            <input class="fields__input" type="text" name="total" value="{{ $summ }}"
                                                   data-popup-total disabled>
                                        </label>
                                    </div>
                                </div>
                                <div class="product-data__option">
                                    <label class="checkbox">
                                        <input class="checkbox__input" type="checkbox" name="option" {{ $product->price ? '' : 'disabled' }}>
                                        <span class="checkbox__box"></span>
                                        <span class="checkbox__policy">Резка</span>
                                    </label>
                                </div>
                            </div>
                            <div class="product-data__bottom">
                                <div class="product-data__weight">Общий вес продукции
                                    <span>{{ $product->measure == 'т' ? round($count_weight, 3) . ' т' : $count_weight * $factor_m2_weight * 1000 . ' кг' }} </span>
                                </div>
                                <div class="product-data__action">
                                    <div class="product-data__summary">Итого
                                        <span>{{ $summ }} руб.</span>
                                    </div>
                                    <div class="product-data__add">
                                        <button class="btn btn--content {{ $in_cart ? 'btn-added' : '' }}"
                                                {{ $in_cart ? 'disabled' : '' }}
                                                data-product-id="{{ $product->id }}"
                                                data-product-count="{{ $count_per_tonn }}"
                                                data-product-weight="{{ $count_weight }}"
                                                type="button">
                                                <span>{{ $in_cart ? 'В корзине' : 'Добавить в корзину'}}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="params">
                    <div class="params__title">Характеристики</div>
                    @if(count($params))
                        <div class="params__grid">
                            @php
                                if(count($params) == 2) {
                                    $first_column = 2;
                                    $second_column = 1;
                                }   else {
                                    $first_column = ceil(count($params) / 2);
                                    $second_column = count($params) - $first_column;
                                }
                            @endphp
                            <div class="params__column">
                                <div class="params__fields">
                                    @for($i = 0; $i < $first_column; $i++)
                                        @php
                                            $alias = $params[$i]['alias'];
                                        @endphp
                                        <dl class="params__field">
                                            <dt class="params__param">{{ $params[$i]['name'] }}</dt>
                                            <dd class="params__value">{{ $product->$alias }}</dd>
                                        </dl>
                                    @endfor
                                </div>
                            </div>
                            <div class="params__column">
                                @for($i = $second_column + 1; $i < count($params); $i++)
                                    @php
                                        $alias = $params[$i]['alias'];
                                    @endphp
                                    <dl class="params__field">
                                        <dt class="params__param">{{ $params[$i]['name'] }}</dt>
                                        <dd class="params__value">{{ $product->$alias }}</dd>
                                    </dl>
                                @endfor
                            </div>
                        </div>
                    @elseif(count($add_params))
                        <div class="params__grid">
                            @php
                                $first_column = ceil(count($add_params) / 2);
                                $second_column = count($add_params) - $first_column;
                            @endphp
                            <div class="params__column">
                                <div class="params__fields">
                                    @for($i = 0; $i < $first_column; $i++)
                                        <dl class="params__field">
                                            <dt class="params__param">{{ $add_params[$i]->name }}</dt>
                                            <dd class="params__value">{{ $add_params[$i]->value }}</dd>
                                        </dl>
                                    @endfor
                                </div>
                            </div>
                            <div class="params__column">
                                @for($i = $second_column; $i < count($add_params); $i++)
                                    <dl class="params__field">
                                        <dt class="params__param">{{ $add_params[$i]->name }}</dt>
                                        <dd class="params__value">{{ $add_params[$i]->value }}</dd>
                                    </dl>
                                @endfor
                            </div>
                        </div>
                    @endif
                </div>
                @if(count($features))
                    <div class="features-company">
                        <div class="features-company__grid">
                            @foreach($features as $feat)
                                <div class="features-company__item">
                                    <img class="features-company__icon lazy" src="/"
                                         data-src="{{ \Fanky\Admin\Models\ProductIcon::UPLOAD_URL . $feat->image }}"
                                         alt="alt" width="50" height="42">
                                    <div class="features-company__label">{!! $feat->name !!}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <section class="section">
                    <div class="container">
                        <div class="section__text text-content">
                            {!! $text !!}
                        </div>
                    </div>
                </section>
                @if(count($related))
                    <nav class="related-products">
                        <div class="related-products__title">Похожие товары:</div>
                        <ul class="related-products__list">
                            @foreach($related as $item)
                                <li class="related-products__item">
                                    <a class="related-products__link" href="{{ $item->url }}" title="{{ $item->name }}"
                                       target="_blank">{{ $item->name }}</a>
                                    <span>{{ $item->price ? number_format(\Fanky\Admin\Models\Product::fullPrice($item->price), 2, ',', ' ') . ' ₽' : 'по запросу' }} </span>
{{--                                    <span>{{ $item->price ? $item->getFullPrice() . ' ₽' : 'по запросу' }} </span>--}}
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
            </div>
        </section>
    </main>
@endsection
