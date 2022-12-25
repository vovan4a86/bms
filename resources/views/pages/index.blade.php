@extends('template')
@section('content')
    {{--        @include('blocks.main_slider')--}}
    <div class="scroller swiper" data-page-scroller>
        <div class="scroller__wrapper swiper-wrapper">
            @include('blocks.main_slider')
            <!-- data-background="light"-->
            @if(count($categories))
                <section class="s-catalog swiper-slide" data-background="light">
                    <div class="s-catalog__container container">
                        <div class="s-catalog__grid">
                            @foreach($categories as $item)
                                <div class="s-catalog__item">
                                    @if($item->is_action)
                                        <div class="discount-card lazy" data-bg="{{ $item->getActionImage() }}">
                                            <img class="discount-card__pic lazy" src="/"
                                                 data-src="{{ $item->getActionImage() }}" width="223"
                                                 height="267"
                                                 alt="">
                                            <div class="discount-card__body">
                                                <div class="discount-card__head">
                                                    <div class="discount-card__title">{{ $item->name }}</div>
                                                    <div class="discount-card__text">{{ $item->action_text }}</div>
                                                </div>
                                                <div class="discount-card__prices">
                                                    <div class="discount-card__current-price">От&nbsp;
                                                        <span data-end="₽">{{ $item->action_new_price }}</span>
                                                    </div>
                                                    <div class="discount-card__old-price"
                                                         data-end="₽">{{ $item->action_old_price }}</div>
                                                </div>
                                                <div class="discount-card__link">
                                                    <a href="{{ route('catalog.index') }}"
                                                       class="button button--white button--small btn-reset">
                                                        <span>В каталог</span>
                                                        <svg width="21" height="10" viewBox="0 0 21 10" fill="none"
                                                             xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20.5 5L13.5 0.958548V9.04145L20.5 5ZM0.5 5.7H14.2V4.3H0.5V5.7Z"
                                                                  fill="currentColor"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a class="card-link" href="{{ $item->url }}" title="{{ $item->name }}">
                                            <span class="card-link__count">
                                                {{ $item->getRecurseProductsCount() }}
                                                {{ SiteHelper::getNumEnding($item->getRecurseProductsCount(), ['товар', 'товара', 'товаров']) }}
                                            </span>
                                            <span class="card-link__title">{{ $item->name }}</span>
                                            <img class="card-link__pic lazy" src="/"
                                                 data-src="{{ $item->getImageUrl() }}" width="357" height="326" alt=""/>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                            <div class="s-catalog__item">
                                <a class="catalog-link lazy" data-bg="/static/images/common/catalog-bg.jpg"
                                   href="{{ route('catalog.index') }}" title="Смотреть весь каталог">
                                    <span class="catalog-link__title">Смотреть весь каталог</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="29" height="14" fill="none">
                                        <path fill="currentColor" d="M29 7 17 .072v13.856L29 7ZM0 8.2h18.2V5.8H0v2.4Z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            <!-- data-background="dark"-->
            <section class="callback-block swiper-slide lazy"
                     data-bg="{{ Settings::fileSrc(Settings::get('callback_bg')) }}"
                     data-background="dark" data-swiper-parallax="-100%">
                <div class="callback-block__container container">
                    <div class="callback-block__title">{{ Settings::get('callback_title') ?? '' }}</div>
                    <div class="callback-block__subtitle">{{ Settings::get('callback_text') ?? '' }}</div>
                    <form class="callback-block__grid" action="{{ route('ajax.callback') }}"
                          onsubmit="sendCallback(this, event)">
                        <div class="callback-block__item">
                            <div class="field field--promo">
                                <input class="field__input" type="text" name="name" required>
                                <span class="field__highlight"></span>
                                <span class="field__bar"></span>
                                <label class="field__label">имя</label>
                            </div>
                        </div>
                        <div class="callback-block__item">
                            <div class="field field--promo">
                                <input class="field__input" type="tel" name="phone" required>
                                <span class="field__highlight"></span>
                                <span class="field__bar"></span>
                                <label class="field__label">телефон</label>
                            </div>
                        </div>
                        <div class="callback-block__item">
                            <label class="checkbox checkbox--dark">
                                <input class="checkbox__input" type="checkbox" checked required>
                                <span class="checkbox__box"></span>
                                <span class="checkbox__policy">Даю согласие на обработку персональных данных.
										<a href="{{ route('policy') }}" target="_blank">Пользовательское соглашение</a>
									</span>
                            </label>
                        </div>
                        <div class="callback-block__item">
                            <button class="callback-block__submit submit submit--white btn-reset" name="submit">
                                <span>Заказать звонок</span>
                            </button>
                        </div>
                    </form>
                </div>
            </section>
            <!-- data-background="dark"-->
            <section class="s-about swiper-slide" data-background="dark">
                <div class="s-about__layout">
                    <div class="s-about__content lazy" data-bg="static/images/common/about-bg.jpg">
                        <div class="s-about__container container">
                            <div class="s-about__title">{{ Settings::get('about_title') }}</div>
                            <div class="s-about__subtitle">{{ Settings::get('about_text') }}</div>
                            @if($feats = Settings::get('about_features'))
                                <div class="s-about__grid">
                                    @foreach($feats as $feat)
                                        <div class="s-about__column">
                                            <div class="s-about__decor">
                                                <div class="s-about__icon lazy"
                                                     data-bg="{{ Settings::fileSrc($feat['about_features_image']) }}"></div>
                                            </div>
                                            <div class="s-about__label">{{ $feat['about_features_title'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="s-about__link">
                                <a class="button button--primary" href="{{ url('/about') }}">
                                    <span>о компании</span>
                                    <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="s-about__human lazy"
                         data-bg="{{ Settings::fileSrc(Settings::get('index_about_img')) }}"></div>
                </div>
            </section>
            <!-- data-background="dark"-->
            <section class="s-services swiper-slide lazy" data-bg="{{ Settings::fileSrc(Settings::get('about_bg')) }}"
                     data-background="dark" data-swiper-parallax="-200%">
                @include('pages.unique.about_services')
            </section>
            <!-- data-background="light"-->
            <section class="s-map swiper-slide" data-background="light">
                <div class="s-map__container container">
                    <div class="s-map__title">География
                        <br/>поставок
                    </div>
                    @if($geo_feats = Settings::get('geo_feats'))
                        <div class="s-map__grid">
                            @foreach($geo_feats as $feat)
                                <div class="s-map__item">
                                    <div class="s-map__head">
                                        <div class="s-map__icon lazy"
                                             data-bg="{{ Settings::fileSrc($feat['geo_feat_ico']) }}"></div>
                                        <div class="s-map__subtitle">{{ $feat['geo_feat_title'] }}</div>
                                    </div>
                                    <div class="s-map__body">{{ $feat['geo_feat_text'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <img class="s-map__map lazy" src="/" data-src="/static/images/common/map.svg" width="1260" height="745"
                     alt="">
            </section>
@stop
