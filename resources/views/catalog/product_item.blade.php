<div class="t-catalog__grid t-catalog__grid--body">
    <div class="t-catalog__col t-catalog__col--wide" data-caption="Наименование">
        <a class="t-catalog__link" href="{{ $item->url }}">{{ $item->name }}</a>
    </div>
    <div class="t-catalog__col" data-caption="Размер">
        <div class="t-catalog__value">{{ $item->size }}</div>
    </div>
    <div class="t-catalog__col t-catalog__col--wide" data-caption="Марка">
        <div class="t-catalog__value">{{ $item->steel }}</div>
    </div>
    <div class="t-catalog__col" data-caption="Длина">
        <div class="t-catalog__value">{{ $item->length }}</div>
    </div>
    <div class="t-catalog__col t-catalog__col--wide" data-caption="Цена, руб">
        <div class="t-catalog__row">
            @if($item->price)
                <div class="t-catalog__value">{{ $item->price }} ₽</div>
            @else
                <div class="t-catalog__value">Под заказ</div>
            @endif
            <div class="t-catalog__cart">
                <button class="cart-btn btn-reset" type="button"
                        aria-label="Добавить в корзину">
                    <svg class="svg-sprite-icon icon-cart">
                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
