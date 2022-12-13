<!-- homepage && 'header--home'-->
<!-- headerIsWhite && 'header--white'-->
<!-- headerIsBlack && 'header--black'-->
<header class="header {{ Request::url() == route('main') ? 'header--home' : null }}
                      {{ isset($headerIsWhite) ? 'header--white' : null }}
                      {{ isset($headerIsBlack) ? 'header--black' : null }}">
    <div class="header__top">
        <div class="header__container header__container--top container">
            @include('blocks.show_small_region_confirm')
            <div class="header__info">
                @if($topMenu)
                <nav class="header__top-nav">
                    <ul class="list-reset">
                        @foreach($topMenu as $item)
                            <li>
                                <a href="{{ $item->url }}">{{ $item->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
                @endif
                <button class="header__callback btn-reset" type="button" data-popup data-src="#callback" aria-label="Заказать звонок">
                    <svg width="15" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 .975C15 .69 14.812.5 14.531.5H.47C.188.5 0 .69 0 .975v18.05c0 .285.188.475.469.475H14.53c.281 0 .469-.19.469-.475V.975ZM13.125 2.4v12.35H9.469c0 .95-.89 1.9-1.922 1.9-1.031 0-1.922-.95-1.922-1.9h-3.75V2.4h11.25Z" fill="currentColor"
                        />
                    </svg>
                    <span>Заказать звонок</span>
                </button>
                <div class="header__messengers">
                    <div class="messenger">
                        <a class="messenger__item" href="https://wa.me/{{ preg_replace('/[^\d]+/', '', Settings::get('header_whatsapp')) }}" title="Написать в Whatsapp">
                            <span class="lazy" data-bg="static/images/common/ico_wa.svg"></span>
                        </a>
                        <a class="messenger__item" href="https://t.me/+{{ preg_replace('/[^\d]+/', '', Settings::get('header_telegram')) }}" title="Написать в Telegram">
                            <span class="lazy" data-bg="static/images/common/ico_telegram.svg"></span>
                        </a>
                    </div>
                </div>
                <a class="header__phone" href="tel:+{{ preg_replace('/[^\d]+/', '', Settings::get('header_phone')) }}">{{ Settings::get('header_phone') }}</a>
            </div>
        </div>
    </div>
    <div class="header__bottom">
        <div class="header__container header__container--bottom container">
            <div class="header__grid">
                <!-- if homepage-->
                <a class="header__logo logo lazy" href="{{ route('main') }}" data-bg="static/images/common/logo.svg" data-white="static/images/common/logo.svg" data-dark="static/images/common/logo--accent.svg"></a>
                <div class="header__nav">
                    <div class="top-nav">
                        <button class="top-nav__catalog btn-reset" type="button" data-open-catalog aria-label="Каталог товаров">
                            <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.34983 0.464844H17.1505C17.9124 0.464844 18.53 1.08249 18.53 1.84441C18.53 2.60632 17.9124 3.22397 17.1505 3.22397H2.34983C1.58792 3.22397 0.970268 2.60632 0.970268 1.84441C0.970268 1.08249 1.58792 0.464844 2.34983 0.464844Z" fill="currentColor"
                                />
                                <path d="M2.36023 5.45801H12.2219C12.9839 5.45801 13.6016 6.0757 13.6016 6.83767C13.6016 7.59963 12.9839 8.21733 12.2219 8.21733H2.36023C1.59827 8.21733 0.980572 7.59963 0.980572 6.83767C0.980572 6.0757 1.59826 5.45801 2.36023 5.45801Z" fill="currentColor"
                                />
                                <path d="M2.34993 10.7129H17.1504C17.9123 10.7129 18.53 11.3306 18.53 12.0925C18.53 12.8545 17.9123 13.4722 17.1504 13.4722H2.34993C1.58796 13.4722 0.970268 12.8545 0.970268 12.0925C0.970268 11.3306 1.58796 10.7129 2.34993 10.7129Z" fill="currentColor"
                                />
                            </svg>
                            <span>Каталог товаров</span>
                        </button>
                        <button class="top-nav__catalog top-nav__catalog--mobile btn-reset" type="button" data-mobile-catalog aria-label="Каталог товаров">
                            <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.34983 0.464844H17.1505C17.9124 0.464844 18.53 1.08249 18.53 1.84441C18.53 2.60632 17.9124 3.22397 17.1505 3.22397H2.34983C1.58792 3.22397 0.970268 2.60632 0.970268 1.84441C0.970268 1.08249 1.58792 0.464844 2.34983 0.464844Z" fill="currentColor"
                                />
                                <path d="M2.36023 5.45801H12.2219C12.9839 5.45801 13.6016 6.0757 13.6016 6.83767C13.6016 7.59963 12.9839 8.21733 12.2219 8.21733H2.36023C1.59827 8.21733 0.980572 7.59963 0.980572 6.83767C0.980572 6.0757 1.59826 5.45801 2.36023 5.45801Z" fill="currentColor"
                                />
                                <path d="M2.34993 10.7129H17.1504C17.9123 10.7129 18.53 11.3306 18.53 12.0925C18.53 12.8545 17.9123 13.4722 17.1504 13.4722H2.34993C1.58796 13.4722 0.970268 12.8545 0.970268 12.0925C0.970268 11.3306 1.58796 10.7129 2.34993 10.7129Z" fill="currentColor"
                                />
                            </svg>

                        </button>
                        @if($mainMenu)
                            <nav class="top-nav__nav" itemscope itemtype="https://schema.org/SiteNavigationElement" aria-label="Меню">
                                <ul class="top-nav__list list-reset" itemprop="about" itemscope itemtype="https://schema.org/ItemList">
                                    @foreach($mainMenu as $item)
                                        <li class="top-nav__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ItemList">
                                            <a class="top-nav__link" href="{{ $item->url }}" itemprop="url">{{ $item->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
                <div class="header__actions">
                    <button class="header__search btn-reset" type="button" data-search-popup data-src="#search" aria-label="Поиск по сайту">
                        <svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.9258 13.5192C15.3027 11.0743 15.3027 7.11035 12.9258 4.66543C10.549 2.22052 6.69531 2.22052 4.31844 4.66543C1.94158 7.11035 1.94158 11.0743 4.31844 13.5192C6.69531 15.9642 10.549 15.9642 12.9258 13.5192ZM14.3441 14.9781C17.5043 11.7276 17.5043 6.45717 14.3441 3.20654C11.1839 -0.044107 6.06032 -0.044107 2.90015 3.20654C-0.260013 6.45717 -0.260013 11.7276 2.90015 14.9781C6.06032 18.2288 11.1839 18.2288 14.3441 14.9781Z"
                                  fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.1117 21.4365L12.5808 14.7186L13.9991 13.2598L20.53 19.9776L19.1117 21.4365Z" fill="currentColor" />
                        </svg>

                    </button>
                    <a class="basket" href="javascript:void(0)" title="Перейти в корзину">
                        <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2.6838 3.16377H0.0300293V0.801758H4.29171L5.26419 4.34478H22.03L18.6533 16.1548H6.24958L2.6838 3.16377ZM5.91252 6.70679L7.85749 13.7928H17.0645L19.0905 6.70679H5.91252Z" fill="currentColor" />
                            <path d="M8.67434 21.4694C9.74851 21.4694 10.6193 20.5176 10.6193 19.3436C10.6193 18.1695 9.74851 17.2178 8.67434 17.2178C7.60017 17.2178 6.72937 18.1695 6.72937 19.3436C6.72937 20.5176 7.60017 21.4694 8.67434 21.4694Z" fill="currentColor" />
                            <path d="M16.2382 21.4694C17.3123 21.4694 18.1832 20.5176 18.1832 19.3436C18.1832 18.1695 17.3123 17.2178 16.2382 17.2178C15.164 17.2178 14.2932 18.1695 14.2932 19.3436C14.2932 20.5176 15.164 21.4694 16.2382 21.4694Z" fill="currentColor" />
                        </svg>
                        <span class="basket__count" data-count="3"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="overlay-nav">
            <div class="overlay-nav__container container">
                <div class="overlay-nav__grid tab-core" data-catalog-tabs>
                    <!-- navigation-->
                    <div class="overlay-nav__navigation">
                        <div class="overlay-nav__link tab-core__nav is-active" data-open="Сортовой прокат">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_beam.svg"></div>
                                <span>Сортовой прокат</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Трубный прокат">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_tubes.svg"></div>
                                <span>Трубный прокат</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Цветной прокат">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_ingot.svg"></div>
                                <span>Цветной прокат</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Нержавеющий прокат">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_steel.svg"></div>
                                <span>Нержавеющий прокат</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Сантехарматура">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_pipeline.svg"></div>
                                <span>Сантехарматура</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Поковки">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_pipe.svg"></div>
                                <span>Поковки</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Сварочные материалы">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_mask.svg"></div>
                                <span>Сварочные материалы</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Асбестоцементные материалы">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_tube.svg"></div>
                                <span>Асбестоцементные материалы</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Листовой прокат">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_fraction.svg"></div>
                                <span>Листовой прокат</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Металлоизделия">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_metal.svg"></div>
                                <span>Металлоизделия</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                        <div class="overlay-nav__link tab-core__nav" data-open="Кровельные и фасадные материалы">
                            <div class="overlay-nav__label">
                                <div class="overlay-nav__icon lazy" data-bg="static/images/common/ico_material.svg"></div>
                                <span>Кровельные и фасадные материалы</span>
                            </div>
                            <svg class="svg-sprite-icon icon-right">
                                <use xlink:href="static/images/sprite/symbol/sprite.svg#right"></use>
                            </svg>
                        </div>
                    </div>
                    <!-- content-->
                    <div class="overlay-nav__content">
                        <div class="overlay-nav__view tab-core__view is-active" data-view="Сортовой прокат">
                            <div class="overlay-nav__lists">
                                <ul class="overlay-nav__list list-reset">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <strong>Арматура, катанка</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Арматура рифленая А3</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Арматура гладкая А1</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Арматура Ат800</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Катанка</a>
                                    </li>
                                </ul>
                                <ul class="overlay-nav__list list-reset">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <strong>Балка, швеллер</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Балки (Двутавр)</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Балки (Двутавр) низколегированные</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Швеллер</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Швеллер низколегированный</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Швеллер гнутый</a>
                                    </li>
                                </ul>
                                <ul class="overlay-nav__list list-reset">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <strong>Уголок</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Уголок равнополочный</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Уголок равнополочный низколегированный</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Уголок неравнополочный</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Уголок нержавеющий никельсодержащий</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Уголок равнополочный судостроительны</a>
                                    </li>
                                </ul>
                                <ul class="overlay-nav__list list-reset">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <strong>Круг</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Круг г/к</a>
                                    </li>
                                </ul>
                                <ul class="overlay-nav__list list-reset">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <strong>Полоса, квадрат</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Полоса г/к</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Полоса г/к оцинкованная</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Полоса нержавеющая никельсодержащая</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Квадрат горячекатаный</a>
                                    </li>
                                </ul>
                                <ul class="overlay-nav__list list-reset">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <strong>Оцинкованный прокат</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Полоса оцинкованная</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Круг оцинкованный</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Труба оцинкованная</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Лист оцинкованный</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="overlay-nav__actions">
                                <a class="action-link action-link--green" href="javascript:void(0)" title="Латунный квадрат">
                                    <img class="action-link__picture lazy" src="/" data-src="static/images/common/action-1.png" alt="" width="153" height="161" />
                                    <span class="action-link__title">Латунный квадрат</span>
                                    <span class="action-link__subtitle">ЛС59-1 КВ 14 L=3000</span>
                                    <span class="action-link__price">от&nbsp;
												<span class="action-link__current">587</span>&nbsp;₽/кг</span>
                                </a>
                                <a class="action-link action-link--blue" href="javascript:void(0)" title="Лист медный">
                                    <img class="action-link__picture lazy" src="/" data-src="static/images/common/action-2.png" alt="" width="166" height="174" />
                                    <span class="action-link__title">Лист медный</span>
                                    <span class="action-link__subtitle">10х600х1500 М1 мягкий</span>
                                    <span class="action-link__price">от&nbsp;
												<span class="action-link__current">882</span>&nbsp;₽/кг</span>
                                </a>
                            </div>
                        </div>
                        <div class="overlay-nav__view tab-core__view" data-view="Трубный прокат">Трубный прокат</div>
                        <div class="overlay-nav__view tab-core__view" data-view="Цветной прокат">Цветной прокат</div>
                        <div class="overlay-nav__view tab-core__view" data-view="Нержавеющий прокат">Нержавеющий прокат</div>
                    </div>
                </div>
            </div>
            <div class="overlay-nav__backdrop"></div>
        </div>
    </div>
</header>
{{--<header class="header">--}}
{{--    <div class="header__top">--}}
{{--        <div class="container header__container">--}}
{{--            <div class="header__column">--}}
{{--                <a class="header__logo logo" href="{{ route('main') }}" title="Сталь Сервис">--}}
{{--                    <img class="lazy" data-src="/static/images/common/logo.svg" src="/" alt="Сталь Сервис" width="409" height="49">--}}
{{--                </a>--}}
{{--                <div class="header__city city">--}}
{{--                    <a class="city__label" href="{{ route('ajax.show-popup-cities') }}" data-fancybox data-type="ajax">--}}
{{--                        @if(isset($current_city) && $current_city)г. {{ $current_city->name }}@else Екатеринбург@endif--}}
{{--                        <svg class="svg-sprite-icon icon-dropdown">--}}
{{--                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#dropdown"></use>--}}
{{--                        </svg>--}}
{{--                    </a>--}}
{{--                    @include('blocks.show_small_region_confirm')--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="header__column">--}}
{{--                <a class="header__send" href="#message" data-popup title="Написать нам">--}}
{{--                    <svg class="svg-sprite-icon icon-email">--}}
{{--                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#email"></use>--}}
{{--                    </svg>--}}
{{--                    <span>Написать нам</span>--}}
{{--                </a>--}}
{{--                <a class="header__send" href="#callback" data-popup title="Заказать звонок">--}}
{{--                    <svg class="svg-sprite-icon icon-phone">--}}
{{--                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#phone"></use>--}}
{{--                    </svg>--}}
{{--                    <span>Заказать звонок</span>--}}
{{--                </a>--}}
{{--                <a class="header__phone" href="tel:+{{ preg_replace('/[^\d]+/', '', Settings::get('header_phone')) }}">{{ Settings::get('header_phone') }}</a>--}}
{{--                <button class="header__hamburger hamburger hamburger--collapse" aria-label="Мобильное меню" data-open-overlay>--}}
{{--							<span class="hamburger-box">--}}
{{--								<span class="hamburger-inner"></span>--}}
{{--							</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="header__content">--}}
{{--        <div class="container header__container">--}}
{{--            <div class="header__column">--}}
{{--                <div class="header__catalog" title="Каталог продукции" aria-label="Каталог продукции">--}}
{{--                    <svg class="svg-sprite-icon icon-list">--}}
{{--                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#list"></use>--}}
{{--                    </svg>--}}
{{--                    <span>Каталог продукции</span>--}}
{{--                    <div class="catalog-header" tabindex="-1">--}}
{{--                        <!-- data-nav-tabs-->--}}
{{--                        <div class="catalog-header__content" data-nav-tabs>--}}
{{--                            <div class="catalog-header__nav">--}}
{{--                                @foreach($catalogTop as $topItem)--}}
{{--                                        <a class="catalog-header__link {{ $loop->iteration == 1 ? 'is-active' : '' }}"--}}
{{--                                       href="{{$topItem->url}}" title="{{ $topItem->name }}"--}}
{{--                                       data-open="{{ $loop->iteration }}">--}}
{{--                                        <span>{{ $topItem->name }}</span>--}}
{{--                                        @if(count($topItem->getAllPublicChildren()))--}}
{{--                                            <svg class="svg-sprite-icon icon-caret">--}}
{{--                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#caret"></use>--}}
{{--                                            </svg>--}}
{{--                                        @endif--}}
{{--                                    </a>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                            @foreach($catalogTop as $topItem)--}}
{{--                                @if(count($subItems = $topItem->getAllPublicChildren()))--}}
{{--                                    <div class="catalog-header__products {{ $loop->iteration == 1 ? 'is-active' : '' }}" data-view="{{ $loop->iteration }}">--}}
{{--                                        <ul class="catalog-header__list">--}}
{{--                                            @foreach($subItems as $subItem)--}}
{{--                                                @if($loop->iteration > 15)--}}
{{--                                                    @continue--}}
{{--                                                @else--}}
{{--                                                    <li class="catalog-header__item">--}}
{{--                                                        <a class="catalog-header__product" href="{{ $subItem->url }}"--}}
{{--                                                           title="{{ $subItem->name }}">{{ $subItem->name }}</a>--}}
{{--                                                    </li>--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}
{{--                                                <li class="catalog-header__item">--}}
{{--                                                    <a class="catalog-header__product" href="{{ $topItem->url }}"--}}
{{--                                                       title="Весь каталог" data-link-catalog="">Весь каталог</a>--}}
{{--                                                </li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <nav class="header__nav nav-header">--}}
{{--                    <ul class="nav-header__list list-reset">--}}
{{--                        @foreach($topMenu as $topItem)--}}
{{--                            <li class="nav-header__item">--}}
{{--                                <a class="nav-header__link" href="{{ $topItem->url }}" title="{{ $topItem->name }}">{{ $topItem->name }}</a>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                </nav>--}}
{{--            </div>--}}
{{--            <div class="header__column header__column--wide">--}}
{{--                <form class="header__search search-header" action="{{ route('search') }}">--}}
{{--                    <input class="search-header__input" type="search" name="q" value="{{ Request::get('q') }}"--}}
{{--                           placeholder="Поиск" aria-label="Поиск по сайту" required>--}}
{{--                    <button class="search-header__button">--}}
{{--                        <svg class="svg-sprite-icon icon-search">--}}
{{--                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#search"></use>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--            @include('blocks.header_cart')--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</header>--}}
