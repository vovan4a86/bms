<!-- data-background="dark"-->
@if($slider = Settings::get('main_slider'))
    <section class="hero swiper-slide" data-background="dark">
        <div class="hero__slider swiper" data-main-slider>
            <div class="hero__wrapper swiper-wrapper">
                @foreach($slider as $slide)
                    <div class="hero__slide swiper-slide">
                        <div class="hero__content container">
                            <div class="hero__title">{{ $slide['main_slider_title'] ?? '' }}</div>
                            <div class="hero__text">{{ $slide['main_slider_text'] ?? '' }}</div>
                            <div class="hero__action">
                                @if($slide['main_slider_button'])
                                    <a class="button button--primary"
                                       href="{{ $slide['main_slider_link'] ?? route('main') }}"
                                       title="{{ $slide['main_slider_button']}} ">
                                        <span>{{ $slide['main_slider_button'] ?? '' }}</span>
                                        <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            @if($features = explode(';', $slide['main_slider_features']))
                                <div class="hero__features">
                                    <div class="features-list">
                                        @foreach($features as $feat)
                                            <div class="features-list__item">
                                                <div class="features-list__icon">
                                                    <svg class="svg-sprite-icon icon-accepted">
                                                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                                    </svg>
                                                </div>
                                                <div class="features-list__label">{!! $feat !!}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="hero__background">
                            @if($slide['main_slider_video'])
                                <video src="{{ Settings::fileSrc($slide['main_slider_video']) }}"
                                       poster="{{ Settings::fileSrc($slide['main_slider_image']) }}" autoplay muted loop
                                       playsinline></video>
                            @elseif(!$slide['main_slider_video'] && $slide['main_slider_image'])
                                <picture>
                                    <img class="swiper-lazy" src="/"
                                         data-src="{{ Settings::fileSrc($slide['main_slider_image']) }}" alt="">
                                </picture>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="hero__pagination swiper-pagination"></div>
                @if(count($slider) > 1)
                    <div class="hero__nav">
                        <div class="slider-nav">
                            <button class="slider-nav__btn slider-nav__btn--left btn-reset" type="button"
                                    data-hero-prev>
                                <svg width="19" height="19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.868 3.58 5.948 9.5l5.92 5.92" stroke="#fff" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button class="slider-nav__btn slider-nav__btn--right btn-reset" type="button"
                                    data-hero-next>
                                <svg width="19" height="19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="m7.132 3.58 5.92 5.92-5.92 5.92" stroke="#fff" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
