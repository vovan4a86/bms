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
                                @if($product->in_stock)
                                    <span>В наличии</span>
                                    <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor"
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @else
                                    <div class="p-status out-stock">
                                        <span>Под заказ</span>
                                    </div>
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
                                    <dl>
                                        <dt>Размер</dt>
                                        <dd>{{ $product->size }}</dd>
                                    </dl>
                                    <dl>
                                        <dt>Толщина</dt>
                                        <dd>{{ $product->wall }}</dd>
                                    </dl>
                                    <dl>
                                        <dt>ГОСТ</dt>
                                        <dd>{{ $product->gost }}</dd>
                                    </dl>
                                    <dl>
                                        <dt>Сталь</dt>
                                        <dd>{{ $product->steel }}</dd>
                                    </dl>
                                </div>
                                @if($product->getAnyPrice())
                                    @include($product->getProductOrderView())
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
                                                <button class="cart-btn btn-reset {{ $item->getAnyPrice() ?? 'disabled' }}"
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
                            <h1>Особенности профильных труб</h1>
                            <p>Стальная профильная труба может иметь различные габариты и сечения, а также длины готовых
                                изделий, и это все регламентировано ГОСТом, в котором прописано использовать для
                                производства металл толщиной 1-14 мм. Протяжение боковых плоскостей составляет
                                10-180 мм. Металлоизделия нарезаются на куски длиной 1-12 метров. За счет этого можно
                                заказать металлоизделия любых размеров и протяженности.</p>
                            <p>Для производства металлических профильных труб используются качественные прочные стали
                                общего назначения. Они могут использоваться в различных сферах – при сооружении
                                трубопроводов и газопроводов, для создания ограждений, отделки помещений, строительства
                                объектов различного масштаба. Это универсальная металлопродукция, которая удобно
                                сваривается разными видами сварки, при этом не меняется структура металла, а ценные
                                прочностные свойства сохраняются. Можно крепить их с помощью крабов, гнуть на
                                специальных станках, использовать трубогибы.</p>
                            <h2>Особенности профильных труб</h2>
                            <p>Стальная профильная труба может иметь различные габариты и сечения, а также длины готовых
                                изделий, и это все регламентировано ГОСТом, в котором прописано использовать для
                                производства металл толщиной 1-14 мм. Протяжение боковых плоскостей составляет
                                10-180 мм. Металлоизделия нарезаются на куски длиной 1-12 метров. За счет этого можно
                                заказать металлоизделия любых размеров и протяженности.</p>
                        </div>
                        @if(count($related))
                            <div class="b-related">
                                <div class="b-related__title">Похожие товары</div>
                                <div class="b-related__list">
                                    @foreach($related as $item)
                                        <div class="b-related__item">
                                            <a class="b-related__link" href="{{ $item->url }}">{{ $item->name }}</a>
                                            <div class="b-related__value">{{ $item->getAnyPrice() }} ₽</div>
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
