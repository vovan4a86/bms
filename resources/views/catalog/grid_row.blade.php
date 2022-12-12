<div class="catalog-list__card">
    <!-- card-->
    <div class="card swiper-slide">
        @if($item->is_action)
            <div class="card__badge">%</div>
        @endif
        <a class="card__preview" href="{{ $item->url }}" title="{{ $item->name }}">
            <img class="card__picture lazy"
                 src="{{ $item->showAnyImage() }}"
                 data-src="{{ $item->showAnyImage() }}"
                 width="200" height="130" alt="{{ $item->name }}">
        </a>
        <div class="card__status">
            @if($item->in_stock == 1)
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
            @elseif($item->in_stock == 2)
                <div class="product-status product-status--out-stock">
                    Под заказ
                </div>
            @else
                <div class="product-status product-status--out-stock">
                    Временно отсутствует
                </div>
            @endif
        </div>
        <h3 class="card__title">
            <a href="{{ $item->url }}">{{ $item->name }}</a>
        </h3>
        <div class="card__price price-card">
            <span class="price-card__label">Цена:</span>
            <span class="price-card__value">{{ $item->price ? $item->getFullPrice() . ' ₽' : 'по запросу' }}</span>
            <span class="price-card__counts">{{ $item->price ? '/ ' . $item->measure : '' }}</span>
        </div>
            @include('catalog.card_actions')
    </div>
</div>
