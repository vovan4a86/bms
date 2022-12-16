<div class="catalog-list__row">
    <div class="catalog-list__grid">
        <div class="catalog-list__column catalog-list__column--one">
            <a href="{{ $item->url }}" title="{{ $item->name }}">
                <img class="catalog-list__picture" src="{{ $item->showAnyImage() }}"
                     data-src="{{ $item->showAnyImage() }}"
                     alt="{{ $item->name }}"
                     width="112" height="61">
            </a>
        </div>
        <div class="catalog-list__column catalog-list__column--two">
            <a class="catalog-list__text" href="{{ $item->url }}">
                {{ $item->name }}</a>
        </div>
        <div class="catalog-list__column catalog-list__column--one">
            @php
                $alias = null;
                if(count($filters) > 0) $alias = $filters[0]->alias;
            @endphp
            @if($alias)
                {{ $item->$alias }}{{ $filters[0]->measure }}
            @endif
        </div>
        <div class="catalog-list__column catalog-list__column--one">
            @php
                $alias = null;
                if(count($filters) > 1) $alias = $filters[1]->alias;
            @endphp
            @if($alias)
                {{ $item->$alias }}{{ $filters[1]->measure }}
            @endif
        </div>
        <div class="catalog-list__column catalog-list__column--two">
            <div class="catalog-list__actions">
                <div class="catalog-list__price">
                    @if($item->price)
                    {{ $item->getFullPrice() }}
                    <span>руб. / {{ $item->measure }}</span>
                    @else
                        по запросу
                    @endif
                </div>
                <button class="catalog-list__add" type="button" aria-label="Добавить в корзину"
                        data-product-id="{{ $item->id }}">
                    <svg class="svg-sprite-icon icon-cart">
                        <use xlink:href="//static/images/sprite/symbol/sprite.svg#cart"></use>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
