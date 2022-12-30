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
            @if($item->getMeasurePrice())
                <div class="t-catalog__value">{{ number_format($item->getMeasurePrice(), 2, ',', ' ') }} ₽</div>
            @else
                <div class="t-catalog__value">Под заказ</div>
            @endif
            <div class="t-catalog__cart">
                @if($item->measure == 'т')
                    <button class="cart-btn btn-reset {{ $item->getMeasurePrice() ? null : 'disabled'}}" type="button"
                            onclick="addToCart({{$item->id}})"
                            aria-label="Добавить в корзину">
                        <svg class="svg-sprite-icon icon-cart">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                        </svg>
                    </button>
                @else
                    <button class="cart-btn btn-reset {{ $item->getMeasurePrice() ? null : 'disabled'}}" type="button"
                            onclick="addToCartPerItem({{$item->id}})"
                            aria-label="Добавить в корзину">
                        <svg class="svg-sprite-icon icon-cart">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
