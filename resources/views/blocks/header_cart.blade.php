<a class="header__cart cart-header" href="{{ route('cart') }}" title="В корзину">
    @if(count($items))
        <div class="cart-header__link">
            <svg class="svg-sprite-icon icon-cart">
                <use xlink:href="//static/images/sprite/symbol/sprite.svg#cart"></use>
            </svg>
            <span class="cart-header__badge">{{ count($items) }}</span>
        </div>
        <div class="cart-header__value">
            <div class="cart-header__count">{{ $count }}</div>
            <div class="cart-header__price" data-end="₽">{{ \Fanky\Admin\Cart::sum() }}</div>
        </div>
    @else
        <div class="cart-header__link">
            <svg class="svg-sprite-icon icon-cart">
                <use xlink:href="//static/images/sprite/symbol/sprite.svg#cart"></use>
            </svg>
            <span class="cart-header__badge">0</span>
        </div>
        <div class="cart-header__value">
            <div class="cart-header__count">Пусто</div>
            <div class="cart-header__price" data-end="₽">0</div>
        </div>
    @endif
</a>
