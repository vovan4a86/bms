@extends('template')
@section('content')
    @include('blocks.bread')
    <!-- class=productLayout && 'layout--product'-->
    <div class="layout layout--product">
        <div class="layout__container container">
            @include('catalog.blocks.aside')
            <div class="layout__content">
                <main>
                    <section class="product">
                        <div class="product__head">
                            <div class="product__title">{{ $product->h1 }}</div>
                            <div class="p-status in-stock">
                                @if(!$product->in_stock || !$product->getMeasurePrice())
                                    <div class="p-status out-stock">
                                        <span>Под заказ</span>
                                    </div>
                                @else
                                    <span>В наличии</span>
                                    <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor"
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="product__grid">
                            <div class="product__preview">
                                <a href="{{ $image }}" data-popup>
                                    <img class="product__pic lazy"
                                         src="{{ $image }}"
                                         data-src="{{ $image }}" width="370" height="330" alt="">
                                </a>
                            </div>
                            <div class="product__data">
                                <div class="product__data-list">
                                    @if($product->size)
                                        <dl>
                                            <dt>Размер</dt>
                                            <dd>{{ $product->size }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->wall)
                                        <dl>
                                            <dt>Толщина</dt>
                                            <dd>{{ $product->wall }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->gost)
                                        <dl>
                                            <dt>ГОСТ</dt>
                                            <dd>{{ $product->gost }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->steel)
                                        <dl>
                                            <dt>Сталь</dt>
                                            <dd>{{ $product->steel }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->length)
                                        <dl>
                                            <dt>Длина</dt>
                                            <dd>{{ $product->length }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->type)
                                        <dl>
                                            <dt>Тип</dt>
                                            <dd>{{ $product->type }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->diameter)
                                        <dl>
                                            <dt>Диаметр</dt>
                                            <dd>{{ $product->diameter }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->py)
                                        <dl>
                                            <dt>Py</dt>
                                            <dd>{{ $product->py }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->brand)
                                        <dl>
                                            <dt>Бренд</dt>
                                            <dd>{{ $product->brand }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->model)
                                        <dl>
                                            <dt>Модель</dt>
                                            <dd>{{ $product->model }}</dd>
                                        </dl>
                                    @endif
                                    @if($product->comment )
                                        <dl>
                                            <dt>Пояснение</dt>
                                            <dd>{{ $product->comment }}</dd>
                                        </dl>
                                    @endif
                                </div>
                                @if($product->measure == 'т' && $product->price)
                                    @include('catalog.blocks.product_order_tonn')
                                @elseif($product->measure == 'шт' && $product->price_per_item)
                                    @include('catalog.blocks.product_order_item')
                                @elseif($product->measure == 'м' && $product->price_per_metr)
                                    @include('catalog.blocks.product_order_metr')
                                @elseif($product->measure == 'кг' && $product->price_per_kilo)
                                    @include('catalog.blocks.product_order_kilo')
                                @else
                                    @include('catalog.blocks.product_request')
                                @endif
                            </div>
                        </div>
                        @if(count($similar))
                            <div class="related-list">
                                @foreach($similar as $item)
                                    <div class="related-list__row">
                                        <a class="related-list__name" href="{{ $item->url }}">{{ $item->name }}</a>
                                        <div class="related-list__info">
                                            <div class="related-list__data">{{ $item->size }}</div>
                                            <div class="related-list__data">{{ $item->steel }}</div>
                                            <div class="related-list__data">{{ $item->length }}</div>
                                            <div class="related-list__action">
                                                <button class="cart-btn btn-reset {{ $item->getMeasurePrice() ?? 'disabled' }}"
                                                        type="button"
                                                        aria-label="Добавить в корзину">
                                                    <svg class="svg-sprite-icon icon-cart">
                                                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="product__text">
                            {!! $product->text !!}
                        </div>
                        @if(count($related))
                            <div class="b-related">
                                <div class="b-related__title">Похожие товары</div>
                                <div class="b-related__list">
                                    @foreach($related as $item)
                                        <div class="b-related__item">
                                            <a class="b-related__link" href="{{ $item->url }}">{{ $item->name }}</a>
                                            <div class="b-related__value">{{ $item->getMeasurePrice() }} ₽</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </section>
                </main>
            </div>
        </div>
    </div>
    @include('blocks.callback_form')
@endsection
