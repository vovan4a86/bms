<div class="card swiper-slide">
    @if($product->is_action)
        <div class="card__badge">%</div>
    @endif
    <a class="card__preview" href="{{ $product->url }}" title="{{ $product->name }}">
        @php
            $images = $product->images()->get();
            if(count($images)) {
                $image = \Fanky\Admin\Models\Product::UPLOAD_URL . $images[0]->image;
            } else {
                $image = $product->showCategoryImage($product->catalog_id);
            }
        @endphp
        <img class="card__picture swiper-lazy"
             src="{{ $image }}"
             data-src="{{ $image }}"
             alt="{{ $product->name }}" />
    </a>
    <div class="card__status">
        @if($product->in_stock == 1)
            <div class="product-status product-status--instock">
                В наличи
                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.4375 2.81274L4.0625 7.18755L1.875 5.00024" stroke="#52AA52" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
        @elseif($product->in_stock == 2)
            <div class="product-status product-status--out-stock">Под заказ</div>
        @else
            <div class="product-status product-status--out-stock">Временно отсутствует</div>
        @endif
    </div>
    <h3 class="card__title">
        <a href="{{ $product->url }}">{{ $product->name }}</a>
    </h3>
    <div class="card__price price-card">
        <span class="price-card__label">Цена:</span>
        <span class="price-card__value">{{ $product->price ? $product->getFullPrice() . ' ₽' : 'по запросу' }}</span>
        <span class="price-card__counts">{{ $product->price ? '/ ' . $product->measure : '' }}</span>
    </div>
   @include('cart.card_actions')
</div>
