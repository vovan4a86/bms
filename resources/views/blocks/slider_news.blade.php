@if(count($slider_news))
    <section class="section newses-block">
        <div class="container newses-block__container">
            <div class="section__title">Новости</div>
            <!-- slider nav-->
            <div class="newses-block__nav slider-nav">
                <div class="slider-nav__icon slider-nav__icon--white slider-nav__prev" data-news-prev>
                    <svg class="svg-sprite-icon icon-arrow-prev">
                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow-prev"></use>
                    </svg>
                </div>
                <div class="slider-nav__icon slider-nav__icon--white slider-nav__next" data-news-next>
                    <svg class="svg-sprite-icon icon-arrow">
                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                    </svg>
                </div>
            </div>
            <!-- slider-->
            <div class="newses-block__slider swiper" data-news-slider>
                <div class="newses-block__wrapper swiper-wrapper">
                    @foreach($slider_news as $news)
                        <!-- slide-->
                        <div class="news-card swiper-slide">
                        <div class="news-card__head">
                            <h3 class="news-card__title">
                                <a href="{{ $news->url }}" title="{{ $news->name }}">{{ $news->name }}</a>
                            </h3>
                            <img class="news-card__picture lazy" src="/" data-src="/static/images/common/news.svg" alt="alt" width="60" height="44">
                        </div>
                        <div class="news-card__content">
                            <p>{{ $news->announce }}</p>
                        </div>
                        <div class="news-card__footer">
                            <a class="news-card__link" href="{{ $news->url }}" title="{{ $news->name }}">Подробнее</a>
                            <div class="news-card__date">
                                <span>{{ $news->dateFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif