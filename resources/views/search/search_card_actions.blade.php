@php
    $in_cart = false;
     if(Session::get('cart')) {
     $cart = array_keys(Session::get('cart'));
         if($cart) {
             $in_cart = in_array($item->id, $cart);
         }
     }
@endphp
<div class="card__actions">
    <button class="btn" type="button" data-product-id="{{ $item->id }}" {{ $in_cart ? 'disabled' : '' }} aria-label="Купить">
        @if(!$item->price)
            <span>{{ $in_cart ? 'В корзине' : 'по запросу' }}</span>
        @else
            <span>{{ $in_cart ? 'В корзине' : 'Купить' }}</span>
        @endif
    </button>
    <button class="card__cart {{ $in_cart ? 'btn--added' : ''}}"
            {{ $in_cart ? 'disabled' : '' }} type="button"
            aria-label="Добавить в корзину" data-product-id="{{ $item->id }}">
        <svg class="svg-sprite-icon icon-cart">
            <use xlink:href="/static/images/sprite/symbol/sprite.svg#cart"></use>
        </svg>
    </button>
</div>
