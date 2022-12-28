<div class="product__order">
    <div class="prod-order">
        <div class="prod-order__grid">
            <div class="prod-order__col">
                <label class="prod-order__label">Количество, ?
                    <input class="prod-order__input" type="number" name="weight" value="0,025">
                </label>
            </div>
            <div class="prod-order__col">
                <label class="prod-order__label">Количество, ?
                    <input class="prod-order__input" type="number" name="size" value="">
                </label>
            </div>
            <div class="prod-order__col">
                <div class="prod-order__label">Цена, ?</div>
                <div class="prod-order__input">111 111</div>
            </div>
            <div class="prod-order__col">
                <div class="prod-order__label">Сумма</div>
                <div class="prod-order__input">111 111</div>
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
