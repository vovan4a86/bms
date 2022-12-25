<div class="newses__item news-card">
    <div class="news-card__head">
        <h3 class="news-card__title">
            <a href="{{ $item->url }}" title="{{ $item->name }}">{{ $item->name }}</a>
        </h3>
        <img class="news-card__picture lazy" src="/" data-src="/static/images/common/news.svg"
             alt="alt" width="60" height="44">
    </div>
    <div class="news-card__content">
        {{ $item->announce }}
    </div>
    <div class="news-card__footer">
        <a class="news-card__link" href="{{ $item->url }}"
           title="{{ $item->name }}">Подробнее</a>
        <div class="news-card__date">
            <span>{{ $item->dateFormat('d F Y') }}</span>
        </div>
    </div>
</div>
