<div class="cart-table__row cart-table__row--body">
    <div class="cart-table__col cart-table__col--wide" data-caption="Товар">
        <a class="cart-table__name" href="{{ $item['url'] }}" target="_blank">{{ $item['name'] }}</a>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div class="cart-table__col" data-caption="Цена">
        <div class="cart-table__value">{{ number_format($item['measure_price'], 0, '', ' ') }} руб./ {{ $item['measure'] }}</div>
    </div>
    <div class="cart-table__col" data-caption="Кол-во">
        <div class="cart-table__counter">
            <div class="counter" data-counter>
                <button class="counter__btn counter__btn--prev btn-reset" type="button" aria-label="Меньше">
                    <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.95117 7.59375H12.7762" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <input class="counter__input" type="number" name="count" value="{{ $item['weight'] }}"
                       data-count onchange="cartUpdateCount(this, {{$item['id']}}, {{ $item['measure_price'] }})">
                <button class="counter__btn counter__btn--next btn-reset" type="button" aria-label="Больше">
                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.40527 8.40527H14.8678" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.13574 2.68262V14.1286" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="cart-table__col" data-caption="Сумма">
        <div class="cart-table__value" data-id="{{ $item['id'] }}">
            {{ number_format($item['weight'] * $item['price'], 0, '', ' ') }} руб.
        </div>
    </div>
</div>
