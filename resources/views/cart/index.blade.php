@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="cart-blank section {{ Request::url() === '/' ? '' : 'section--inner' }}">
            @if(count($items) == 0)
                <div class="cart-blank__container container">
                    <img class="cart-blank__picture lazy" src="/" data-src="//static/images/common/cart.svg" alt="alt"
                         width="165" height="165">
                    <h2 class="cart-blank__title">Ваша корзина пока пуста</h2>
                    <p class="cart-blank__text">Воспользуйтесь поиском или
                        <a href="{{ route('catalog.index') }}">каталогом</a>, чтобы найти всё что нужно.</p>
                </div>
            @else
                <form class="cart__container container" action="{{ route('ajax.order') }}"
                      onsubmit="sendOrder(this, event)">
                    @csrf
                    <input type="hidden" name="summ" value="{{ \Fanky\Admin\Cart::sum() }}">
                    <input type="hidden" name="total_weight" value="{{ \Fanky\Admin\Cart::total_weight() }}">
                    <div class="section__links">
                        <h2 class="section__title section__title--cart" data-count="{{ count($items) }}">Корзина</h2>
                        <button class="clear-btn" type="button">
                            <svg class="svg-sprite-icon icon-trash">
                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#trash"></use>
                            </svg>
                            <span>Очистить корзину</span>
                        </button>
                    </div>
                    <div class="cart__head">
                        <div class="cart__body">
                            <div class="cart-table">
                                <div class="cart-table__head">
                                    <div class="cart-table__grid">
                                        <div class="cart-table__column cart-table__column--size-4">
                                            <div class="cart-table__label">Товар</div>
                                        </div>
                                        <div class="cart-table__column cart-table__column--size-2">
                                            <div class="cart-table__label">Цена</div>
                                        </div>
                                        <div class="cart-table__column cart-table__column--size-2">
                                            <div class="cart-table__label">Кол-во.</div>
                                        </div>
                                        <div class="cart-table__column cart-table__column--size-2">
                                            <div class="cart-table__label">Ед. изм.</div>
                                        </div>
                                        <div class="cart-table__column cart-table__column--size-3">
                                            <div class="cart-table__label">Итого с НДС</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- row-->
                                @foreach($items as $item)
                                    @include('cart.table_row')
                                @endforeach
                            </div>
                        </div>
                        @include('blocks.cart_values')
                    </div>
                    <div class="cart__hidden is-hidden" data-cart-hidden>
                        <!-- data-->
                        <div class="cart__data">
                            <div class="cart__title">Доставка и оплата</div>
                            <div class="cart__radios radios radios--row">
                                <div class="radios__button">
                                    <input id="legal" type="radio" name="user" value="0" checked>
                                    <label for="legal" data-radio="show">Юридическое лицо</label>
                                </div>
                                <div class="radios__button">
                                    <input id="private" type="radio" name="user" value="1">
                                    <label for="private" data-radio="hide">Частное лицо</label>
                                </div>
                            </div>
                            <div class="cart__row">
                                <div class="cart__subtitle">Контактные данные</div>
                                <div class="cart__fields">
                                    <div class="cart__field">
                                        <label class="cart__label">Имя *
                                            <input class="cart__input" type="text" name="name"
                                                   placeholder="Представьтесь пожалуйста" autocomplete="off" required>
                                        </label>
                                    </div>
                                    <div class="cart__field">
                                        <label class="cart__label">Телефон *
                                            <input class="cart__input" type="tel" name="phone"
                                                   placeholder="+7 (___) ___-__-__" autocomplete="off" required>
                                        </label>
                                    </div>
                                    <div class="cart__field">
                                        <label class="cart__label">Email
                                            <input class="cart__input" type="text" name="email"
                                                   placeholder="Введите Email" autocomplete="off">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div data-hide-content>
                                <div class="cart__row">
                                    <div class="cart__subtitle">Реквизиты компании</div>
                                    <div class="cart__fields">
                                        <div class="cart__field">
                                            <label class="cart__label">ИНН *
                                                <input class="cart__input" type="text" name="inn"
                                                       placeholder="00 0000 0000" autocomplete="off" required>
                                            </label>
                                        </div>
                                        <div class="cart__field">
                                            <label class="cart__label">Наименование *
                                                <input class="cart__input" type="text" name="company"
                                                       placeholder="Название компании" autocomplete="off" required>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart__upload">
                                    <h4>Карточка компании</h4>
                                    <div class="cart__upload-trigger">
                                        <label class="upload">
                                            <span class="upload__name">Прикрепить файл</span>
                                            <input class="v-hidden" type="file" name="file"
                                                   accept=".jpg, .jpeg, .png, .pdf, .doc, .docs, .xls, .xlsx">
                                        </label>
                                        <span class="upload__status">Размер файла не более 2 мб</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- data-->
                        <div class="cart__data">
                            <div class="cart__title">Способ доставки</div>
                            <div class="cart__radios radios radios--row">
                                <div class="radios__button">
                                    <input id="delivery" type="radio" name="delivery_method" value="0" checked>
                                    <label for="delivery" data-radio="show">Доставка</label>
                                </div>
                                <div class="radios__button">
                                    <input id="self" type="radio" name="delivery_method" value="1">
                                    <label for="self" data-radio="hide">Самовывоз</label>
                                </div>
                            </div>
                            <div class="cart__row">
                                <div class="cart__grids" data-hide-content>
                                    <div class="cart__delivery">
                                        <label class="cart__label">Адрес доставки *
                                            <input class="cart__input" type="text" name="address"
                                                   placeholder="Для расчёта стоимости доставки" autocomplete="off"
                                                   required>
                                        </label>
                                    </div>
                                    <div class="cart__timing">
                                        <label class="cart__label">Период доставки
                                            <select class="select" name="timing">
                                                <option value="В течение дня" selected>В течение дня</option>
                                                <option value="c 09:00 до 13:00">c 09:00 до 13:00</option>
                                                <option value="c 13:00 до 18:00">c 13:00 до 18:00</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="cart__comment">
                                <label class="cart__label">Комментарий
                                    <textarea class="cart__input" rows="4" name="text"
                                              placeholder="Расскажите, как быстрее добраться к вам, укажите информацию, которая может пригодиться "
                                              autocomplete="off"></textarea>
                                </label>
                            </div>
                            <div class="cart__requires">
                                <div class="cart__require" data-start="*">обязательные поля для заполнения</div>
                                <div class="cart__policy">
                                    <label class="checkbox checkbox--small">
                                        <input class="checkbox__input" type="checkbox" name="policy" checked required>
                                        <span class="checkbox__box"></span>
                                        <span class="checkbox__policy">Согласен на
                                                <a href="_ajax-policy.html" data-fancybox data-type="ajax">обработку персональных данных</a>
                                            </span>
                                    </label>
                                </div>
                            </div>
                            <div class="cart__confirm">
                                <button class="btn btn--content btn--cart" type="submit">
                                    <span>Оформить заказ</span>
                                </button>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </section>

    </main>
@endsection
