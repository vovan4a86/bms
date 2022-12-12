@if($slides = Settings::get('main_slider'))
    <section class="hero swiper" data-main-slider>
        <div class="hero__wrapper swiper-wrapper">
            <!-- slide-->
            @foreach($slides as $slide)
                <div class="hero__slide swiper-slide">
                @if($slide['main_slider_image'])
                <div class="hero__bg">
{{--                        @php--}}
{{--                            $file_name = stristr(Settings::fileSrc($slide['main_slider_image']), '.', true);--}}
{{--                        @endphp--}}
                        <picture>
        {{--                    <source media="(max-width: 768px)" srcset="/static/images/common/hero-slide--768.webp" type="image/webp">--}}
{{--                            <source media="(max-width: 768px)" srcset="{{ $file_name }}--768.jpg">--}}
        {{--                    <source media="(max-width: 1024px)" srcset="/static/images/common/hero-slide--1024.webp" type="image/webp">--}}
{{--                            <source media="(max-width: 1024px)" srcset="{{ $file_name }}--1024.jpg">--}}
        {{--                    <source media="(max-width: 1600px)" srcset="/static/images/common/hero-slide--1600.webp" type="image/webp">--}}
{{--                            <source media="(max-width: 1600px)" srcset="{{ $file_name }}--1600.jpg">--}}
        {{--                    <source srcset="/static/images/common/hero-slide.webp" type="image/webp">--}}
                            <img class="hero__picture swiper-lazy" src="{{ Settings::fileSrc($slide['main_slider_image']) }}" data-src="{{ Settings::fileSrc($slide['main_slider_image']) }}" alt="picture">
                        </picture>
                    </div>
                @endif
                <div class="container hero__container">
                    <div class="hero__content">
                        <h2 class="hero__title">{{ $slide['main_slider_title'] }}</h2>
                        <p class="hero__description">{{ $slide['main_slider_text'] }}</p>
                        @if($slide['main_slider_button'])
                            <div class="hero__action">
                                <a class="btn btn--white" href="{{ $slide['main_slider_link'] }}">
                                    <span>{{ $slide['main_slider_button'] }}</span>
                                    <svg class="svg-sprite-icon icon-arrow">
                                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                                    </svg>
                                </a>
                            </div>
                        @endif
                        @include('blocks.fast_order')
                    </div>
                    <div class="hero__pagination"></div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
@endif