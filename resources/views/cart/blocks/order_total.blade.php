<div class="cart__aside">
    <div class="cart-view">
        <div class="cart-view__head">Ваш заказ</div>
        <div class="cart-view__body">
            <div class="cart-view__row">
                <dl class="dl-reset">
                    <dt>Общий вес товаров</dt>
                    @if($total_weight)
                        <dd>{{ $total_weight }} кг</dd>
                    @else
                        <dd>~~~</dd>
                    @endif
                </dl>
            </div>
            <div class="cart-view__total">
                <dl class="dl-reset">
                    <dt>Итого</dt>
                    <dd>{{number_format($sum , 0, '', ' ')}} руб.</dd>
                </dl>
            </div>
            <div class="cart-view__submit">
                <a class="button button--primary button--small" href="{{ route('create-order') }}" title="Продолжить">
                    <span>Продолжить</span>
                </a>
            </div>
        </div>
    </div>
</div>
