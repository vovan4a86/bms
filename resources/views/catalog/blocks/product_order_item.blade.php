<div class="product__order">
    <div class="prod-order">
        <div class="prod-order__grid">
            <div class="prod-order__col">
                <label class="prod-order__label">Количество, {{ $product->measure }}
                    <input class="prod-order__input" type="number" step="1" name="size" value="1" onkeyup="changeItem(this)">
                </label>
            </div>
            <div class="prod-order__col">
                <div class="prod-order__label">Цена, {{ $product->measure }}</div>
                <div class="prod-order__input" name="price">{{ number_format($product->price_per_item, 0, '', ' ') }}</div>
            </div>
            <div class="prod-order__col">
                <div class="prod-order__label">Сумма</div>
                <div class="prod-order__input" name="total">{{ number_format($product->price_per_item, 0, '', ' ') }}</div>
            </div>
        </div>
        <div class="prod-order__action">
            <!-- важно обновлять данные в кнопке-->
            <!-- дальше modules/popup.js-->
            <button class="button button--primary" type="button"
                    data-id="{{ $product->id }}"
                    data-create-order data-src="#order_item"
                    data-title="{{ $product->name }}"
                    data-weight="1"
                    data-size=""
                    data-price="{{ number_format($product->price_per_item, 0 , '', ' ') }}"
                    data-total="{{ $product->price_per_item }}">
                <span>{{ $in_cart ? 'В корзине' : 'Добавить в корзину'}}</span>
            </button>
        </div>
    </div>
</div>
