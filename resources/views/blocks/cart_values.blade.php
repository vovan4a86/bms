<div class="cart__values">
    <div class="cart-data">
        <div class="cart-data__subtitle">Ваш заказ</div>
        @include('cart.blocks.total')
        <div class="cart-data__prices">
            <div class="cart-data__price">
                <dl>
                    <dt>Сумма:</dt>
                    @include('cart.blocks.summ')
                </dl>
            </div>
            <div class="cart-data__price">
                <dl>
                    <dt>Итого:</dt>
                    @include('cart.blocks.full_summ')
                </dl>
            </div>
        </div>
        <ul class="cart-data__labels">
            <li class="cart-data__label">* Все цены с учетом НДС</li>
            <li class="cart-data__label">* Окончательную стоимость заказа Вам сообщит
                менеджер.
            </li>
        </ul>
        <div class="cart-data__action">
            <button class="btn btn--content" type="button" data-proceed-order>
                <span>Оформить заказ</span>
            </button>
        </div>
    </div>
</div>
