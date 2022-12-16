<div class="cart-table__row" id="product-{{$item['id']}}" data-cart-order="{{ $item['id'] }}">
    <div class="cart-table__grid">
        <div class="cart-table__column cart-table__column--size-4">
            <div class="cart-table__product">
                <div class="cart-item">
                    <div class="cart-item__content">
                        <a class="cart-item__link" href="{{ $item['url'] }}"
                           target="_blank">
                            <img class="cart-item__picture" src="{{ $item['image'] }}"
                                 data-src="{{ $item['image'] }}"
                                 width="67" height="44" alt="{{ $item['name'] }}"/>
                        </a>
                        <div class="cart-item__body">
                            <a class="cart-item__title" href="{{ $item['url'] }}"
                               target="_blank">{{ $item['name'] }}</a>
                            <div class="cart-item__status">
                                @if($item['in_stock'] == 1)
                                    <div class="product-status product-status--instock">
                                        В наличии
                                        <svg width="10" height="10"
                                             viewBox="0 0 10 10" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.4375 2.81274L4.0625 7.18755L1.875 5.00024"
                                                  stroke="#52AA52"
                                                  stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                @elseif($item['in_stock'] == 2)
                                    <div class="product-status product-status--out-stock">
                                        Под заказ
                                    </div>
                                @else
                                    <div class="product-status product-status--out-stock">
                                        Временно отсутствует
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cart-table__column cart-table__column--size-2"
             data-field="Цена">
            <div class="cart-table__price"
                 data-currency="{{ $item['price'] > 0 ? '₽' : '' }}">
                {{ $item['price'] > 0 ? number_format(\Fanky\Admin\Models\Product::fullPrice($item['price']), 2, ',', ' ') : '-' }}
            </div>
        </div>
        <div class="cart-table__column cart-table__column--size-2"
             data-field="Кол-во.">
            <div class="cart-table__count" data-cart-weight>{{ $item['count_weight'] }}</div>
        </div>
        <div class="cart-table__column cart-table__column--size-2"
             data-field="Ед. изм.">
            <div class="cart-table__sizing">{{ $item['measure'] }}</div>
        </div>
        <div class="cart-table__column cart-table__column--size-3"
             data-field="Итого с НДС">
            <div class="cart-table__actions">
                <div class="cart-table__price" data-currency="{{ $item['price'] > 0 ? '₽' : '-' }}" data-cart-price>
                    {{ $item['price'] > 0 ? number_format(\Fanky\Admin\Models\Product::fullPrice($item['price']) * $item['count_weight'], 2, ',', ' ') : '' }}
                </div>
                <div class="cart-table__controls">
                    <button class="cart-control cart-control--edit" type="button"
                            data-edit-order="{{ $item['id'] }}"
                            data-order-edit
                            data-name="{{ $item['name'] }}"
                            data-instock="{{ $item['in_stock'] }}"
                            data-price="{{ \Fanky\Admin\Models\Product::fullPrice($item['price']) }}"
                            data-length="{{ $item['length'] ?? $item['dlina'] }}"
                            data-measure="{{ $item['measure'] }}"
                            data-factor="{{ $item['factor'] }}"
                            data-weight="{{ $item['count_weight'] }}"
                            data-count="{{ $item['count_per_tonn'] }}"
                            data-factorweight="{{ $item['factor_m2_weight'] }}"
                            data-src="#edit-order" aria-label="Изменить">
                        <svg class="svg-sprite-icon icon-pencil">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#pencil"></use>
                        </svg>
                    </button>
                    <button class="cart-control cart-control--remove" type="button"
                            data-remove-order="{{ $item['id'] }}"
                            aria-label="Удалить">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.0625 3.9375L3.9375 14.0625"
                                  stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14.0625 14.0625L3.9375 3.9375"
                                  stroke="currentColor" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
