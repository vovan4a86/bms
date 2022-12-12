<div class="offer__item lazy" data-bg="{{ $item->thumb(2) }}">
    <div class="offer__content">
        <h3 class="offer__title">
            <a href="{{ $item->url }}">{{ $item->name }}</a>
        </h3>
        <p class="offer__description">{{ $item->annonce }}</p>
        <a class="offer__link link-offer" href="{{ $item->url }}">
            <span class="link-offer__label">Подробнее</span>
            <span class="link-offer__icon">
                <svg class="svg-sprite-icon icon-arrow">
                    <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                </svg>
            </span>
        </a>
    </div>
</div>