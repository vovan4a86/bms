<div class="product__order">
    <div class="prod-order">
        <div class="prod-order__grid">
            <div class="prod-order__col">
                <label class="prod-order__label">Количество, {{ $product->measure }}
                    <input class="prod-order__input" type="number"
                           name="weight" value="0" onkeyup="ae(this)">
                </label>
            </div>
            <div class="prod-order__col">
                <label class="prod-order__label">Количество, {{ $product->measure2 }}
                    <input class="prod-order__input" type="number"
                           step="1" name="size" value="0" onkeyup="be(this)">
                </label>
            </div>
            <div class="prod-order__col">
                <div class="prod-order__label">Цена, {{ $product->measure }}</div>
                <div class="prod-order__input" name="price">{{ number_format($product->price_per_kilo, 0, '', ' ') }}</div>
            </div>
            <div class="prod-order__col">
                <div class="prod-order__label">Сумма</div>
                <div class="prod-order__input" name="total">0</div>
            </div>
        </div>
        <div class="prod-order__action">
            <!-- важно обновлять данные в кнопке-->
            <!-- дальше modules/popup.js-->
            <button class="button button--primary" type="button"
                    data-id="{{ $product->id }}"
                    data-create-order data-src="{{ $product->measure2 == 'м2' ? '#order_kilo_m2' : '#order_kilo'}}"
                    data-title="{{ $product->name }}"
                    data-weight="1"
                    data-size=""
                    data-k="{{ $product->k }}"
                    data-l="{{ $product->length ?? 0 }}"
                    data-price="{{ $product->price_per_kilo }}"
                    data-total="{{ $product->price_per_kilo }}">
                <span>{{ $in_cart ? 'В корзине' : 'Добавить в корзину'}}</span>
            </button>
        </div>
        @if($product->min_length)
            <div class="prod-order__text">* Минимальная длина при заказе: {{ $product->min_length }} м</div>
        @endif
    </div>
</div>
