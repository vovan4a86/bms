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
                                <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                @else
                                    <span>Нет в наличии</span>
                                @endif
                            </div>
                        </div>
                        <div class="product__grid">
                            <div class="product__preview">
                                <a href="{{ $image }}" data-popup>
                                    <img class="product__pic lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="{{ $image }}" width="370" height="330" alt="">
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
                                <div class="product__order">
                                    <div class="prod-order">
                                        <div class="prod-order__grid">
                                            <div class="prod-order__col">
                                                <label class="prod-order__label">Количество, т
                                                    <input class="prod-order__input" type="number" name="weight" value="0,025">
                                                </label>
                                            </div>
                                            <div class="prod-order__col">
                                                <label class="prod-order__label">Количество, М
                                                    <input class="prod-order__input" type="number" name="size" value="5">
                                                </label>
                                            </div>
                                            <div class="prod-order__col">
                                                <div class="prod-order__label">Цена, т</div>
                                                <div class="prod-order__input">134 000</div>
                                            </div>
                                            <div class="prod-order__col">
                                                <div class="prod-order__label">Сумма</div>
                                                <div class="prod-order__input">136 750</div>
                                            </div>
                                        </div>
                                        <div class="prod-order__action">
                                            <!-- важно обновлять данные в кнопке-->
                                            <!-- дальше modules/popup.js-->
                                            <button class="button button--primary" type="button" data-create-order data-src="#order" data-title="Трубы ВГП оцинкованные 15х2.8 ДУ 6000" data-weight="0,025" data-size="5" data-price="134 000" data-total="136 750">
                                                <span>Добавить в корзину</span>
                                            </button>
                                        </div>
                                        @if($product->min_length)
                                            <div class="prod-order__text">* Минимальная длина при заказе: {{ $product->min_length }} м</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="related-list">
                            <div class="related-list__row">
                                <a class="related-list__name" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                <div class="related-list__info">
                                    <div class="related-list__data">80</div>
                                    <div class="related-list__data">09Г2С</div>
                                    <div class="related-list__data">3000-12000</div>
                                    <div class="related-list__action">
                                        <button class="cart-btn btn-reset" type="button" aria-label="Добавить в корзину">
                                            <svg class="svg-sprite-icon icon-cart">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="related-list__row">
                                <a class="related-list__name" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                <div class="related-list__info">
                                    <div class="related-list__data">80</div>
                                    <div class="related-list__data">09Г2С</div>
                                    <div class="related-list__data">3000-12000</div>
                                    <div class="related-list__action">
                                        <button class="cart-btn btn-reset" type="button" aria-label="Добавить в корзину">
                                            <svg class="svg-sprite-icon icon-cart">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="related-list__row">
                                <a class="related-list__name" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                <div class="related-list__info">
                                    <div class="related-list__data">80</div>
                                    <div class="related-list__data">09Г2С</div>
                                    <div class="related-list__data">3000-12000</div>
                                    <div class="related-list__action">
                                        <button class="cart-btn btn-reset" type="button" aria-label="Добавить в корзину">
                                            <svg class="svg-sprite-icon icon-cart">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product__text">
                            <h1>Особенности профильных труб</h1>
                            <p>Стальная профильная труба может иметь различные габариты и сечения, а также длины готовых изделий, и это все регламентировано ГОСТом, в котором прописано использовать для производства металл толщиной 1-14 мм. Протяжение боковых плоскостей составляет
                                10-180 мм. Металлоизделия нарезаются на куски длиной 1-12 метров. За счет этого можно заказать металлоизделия любых размеров и протяженности.</p>
                            <p>Для производства металлических профильных труб используются качественные прочные стали общего назначения. Они могут использоваться в различных сферах – при сооружении трубопроводов и газопроводов, для создания ограждений, отделки помещений, строительства
                                объектов различного масштаба. Это универсальная металлопродукция, которая удобно сваривается разными видами сварки, при этом не меняется структура металла, а ценные прочностные свойства сохраняются. Можно крепить их с помощью крабов, гнуть на
                                специальных станках, использовать трубогибы.</p>
                            <h2>Особенности профильных труб</h2>
                            <p>Стальная профильная труба может иметь различные габариты и сечения, а также длины готовых изделий, и это все регламентировано ГОСТом, в котором прописано использовать для производства металл толщиной 1-14 мм. Протяжение боковых плоскостей составляет
                                10-180 мм. Металлоизделия нарезаются на куски длиной 1-12 метров. За счет этого можно заказать металлоизделия любых размеров и протяженности.</p>
                        </div>
                        <div class="b-related">
                            <div class="b-related__title">Похожие товары</div>
                            <div class="b-related__list">
                                <div class="b-related__item">
                                    <a class="b-related__link" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                    <div class="b-related__value">70 090 ₽</div>
                                </div>
                                <div class="b-related__item">
                                    <a class="b-related__link" href="javascript:void(0)">Электросварные низколегированные квадратные</a>
                                    <div class="b-related__value">70 090 ₽</div>
                                </div>
                                <div class="b-related__item">
                                    <a class="b-related__link" href="javascript:void(0)">Электросварные низколегированные квадратные</a>
                                    <div class="b-related__value">70 090 ₽</div>
                                </div>
                                <div class="b-related__item">
                                    <a class="b-related__link" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                    <div class="b-related__value">70 090 ₽</div>
                                </div>
                                <div class="b-related__item">
                                    <a class="b-related__link" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                    <div class="b-related__value">70 090 ₽</div>
                                </div>
                                <div class="b-related__item">
                                    <a class="b-related__link" href="javascript:void(0)">Электросварные низколегированные квадратные трубы 60х5, длина 12 м, марка 09Г2С</a>
                                    <div class="b-related__value">70 090 ₽</div>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
    @include('blocks.callback_form')
@endsection
