<section class="section tabs" data-tabs>
    <div class="container tabs__container">
        <div class="tabs__nav">
            <div class="tabs__link is-active" data-open="discount">Товары по акции</div>
            <div class="tabs__link" data-open="popular">Популярные товары</div>
        </div>
        <div class="tabs__views">
            <!-- view discount-->
            <div class="tabs__view is-active" data-view="discount">
                <h2 class="v-hidden">Товары по акции</h2>
                <!-- slider nav-->
                <div class="slider-nav">
                    <div class="slider-nav__icon slider-nav__prev" data-discount-prev>
                        <svg class="svg-sprite-icon icon-arrow-prev">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow-prev"></use>
                        </svg>
                    </div>
                    <div class="slider-nav__icon slider-nav__next" data-discount-next>
                        <svg class="svg-sprite-icon icon-arrow">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                        </svg>
                    </div>
                </div>
                <!-- slider-->
                <div class="tabs__slider swiper" data-discount-slider>
                    <div class="tabs__wrapper swiper-wrapper">
                        <!-- slide-->
                        @foreach($action_products as $product)
                            @include('blocks.product_card_slide')
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- view popular-->
            <div class="tabs__view" data-view="popular">
                <h2 class="v-hidden">Популярные товары</h2>
                <!-- slider nav-->
                <div class="slider-nav">
                    <div class="slider-nav__icon slider-nav__prev" data-popular-prev>
                        <svg class="svg-sprite-icon icon-arrow-prev">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow-prev"></use>
                        </svg>
                    </div>
                    <div class="slider-nav__icon slider-nav__next" data-popular-next>
                        <svg class="svg-sprite-icon icon-arrow">
                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
                        </svg>
                    </div>
                </div>
                <!-- slider-->
                <div class="tabs__slider swiper" data-popular-slider>
                    <div class="tabs__wrapper swiper-wrapper">
                        <!-- slide-->
                        @foreach($popular_products as $product)
                            @include('blocks.product_card_slide')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
