@if($item['measure'] == 'т')
    <div class="cart-table__value">
        {{ number_format($item['weight'] * $item['price'], 0, '', ' ') }} руб.
    </div>
@elseif($item['measure'] == 'кг')
    <div class="cart-table__value">
        {{ number_format($item['weight'] * $item['price_per_kilo'], 0, '', ' ') }} руб.
    </div>
@elseif($item['measure'] == 'м')
    <div class="cart-table__value">
        {{ number_format($item['count'] * $item['price_per_metr'], 0, '', ' ') }} руб.
    </div>
@else
    <div class="cart-table__value">
        {{ number_format($item['count'] * $item['price_per_item'], 0, '', ' ') }} руб.
    </div>
@endif
