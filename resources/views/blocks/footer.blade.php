<!-- data-background="dark"-->
<!-- class=(homepage && 'swiper-slide')-->
<footer class="footer lazy swiper-slide" data-background="dark" data-bg="/static/images/common/footer-bg.jpg">
    <div class="footer__container container">
        <div class="footer__grid">
            <div class="footer__info">
                <a class="footer__logo logo lazy" href="{{ route('main') }}" data-bg="/static/images/common/logo.svg"></a>
                <div class="footer__rows">
                    <div class="footer__row">
                        <div class="footer__label">Приходите и приезжайте</div>
                        <div class="footer__data">{!! Settings::get('footer_address') !!}</div>
                    </div>
                    <div class="footer__row">
                        <div class="footer__label">Звоните</div>
                        <div class="footer__data">
                            <a href="tel:+{{ preg_replace('/[^\d]+/', '', Settings::get('footer_phone')) }}" title="Позвонить нам">{{ Settings::get('footer_phone') }}</a>
                        </div>
                    </div>
                    <div class="footer__row">
                        <div class="footer__label">Пишите</div>
                        <div class="footer__data">
                            <a href="mailto:{{ Settings::get('footer_email') }}" title="Написать нам">{{ Settings::get('footer_email') }}</a>
                        </div>
                        <div class="footer__label">{{ Settings::get('footer_work') }}</div>
                    </div>
                    <div class="footer__row">
                        <div class="footer__label">Мессенджеры</div>
                        <div class="footer__data footer__data--messenger">
                            <div class="messenger messenger--footer">
                                <a class="messenger__item" href="https://wa.me/{{ preg_replace('/[^\d]+/', '', Settings::get('footer_whatsapp')) }}" title="Написать в Whatsapp">
                                    <span class="lazy" data-bg="/static/images/common/ico_wa.svg"></span>
                                </a>
                                <a class="messenger__item" href="https://t.me/+{{ preg_replace('/[^\d]+/', '', Settings::get('footer_telegram')) }}" title="Написать в Telegram">
                                    <span class="lazy" data-bg="/static/images/common/ico_telegram.svg"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer__links">
                    <a class="link-btn link-btn--accent btn-reset" href="{{ route('catalog.index')}}">
                        <svg width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.34983 0.464844H17.1505C17.9124 0.464844 18.53 1.08249 18.53 1.84441C18.53 2.60632 17.9124 3.22397 17.1505 3.22397H2.34983C1.58792 3.22397 0.970268 2.60632 0.970268 1.84441C0.970268 1.08249 1.58792 0.464844 2.34983 0.464844Z" fill="currentColor"
                            />
                            <path d="M2.36023 5.45801H12.2219C12.9839 5.45801 13.6016 6.0757 13.6016 6.83767C13.6016 7.59963 12.9839 8.21733 12.2219 8.21733H2.36023C1.59827 8.21733 0.980572 7.59963 0.980572 6.83767C0.980572 6.0757 1.59826 5.45801 2.36023 5.45801Z" fill="currentColor"
                            />
                            <path d="M2.34993 10.7129H17.1504C17.9123 10.7129 18.53 11.3306 18.53 12.0925C18.53 12.8545 17.9123 13.4722 17.1504 13.4722H2.34993C1.58796 13.4722 0.970268 12.8545 0.970268 12.0925C0.970268 11.3306 1.58796 10.7129 2.34993 10.7129Z" fill="currentColor"
                            />
                        </svg>
                        <span>Каталог товаров</span>
                    </a>
                </div>
            </div>
            <div class="footer__nav">
                <div class="footer__column">
                    <div class="footer__title">Каталог</div>
                    @if($footerCatalog)
                        <nav class="footer__menu footer-nav" itemscope itemtype="https://schema.org/SiteNavigationElement" aria-label="Меню">
                            <ul class="footer-nav__list list-reset" itemprop="about" itemscope itemtype="https://schema.org/ItemList">
                                @foreach($footerCatalog as $item)
                                <li class="footer-nav__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ItemList">
                                    <a class="footer-nav__link" href="{{ $item->url }}" itemprop="url">{{ $item->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </nav>
                    @endif
                </div>
                <div class="footer__column">
                    <div class="footer__title">Покупателям</div>
                    @if($footerMenu)
                    <nav class="footer__menu footer-nav" itemscope itemtype="https://schema.org/SiteNavigationElement" aria-label="Меню">
                        <ul class="footer-nav__list list-reset" itemprop="about" itemscope itemtype="https://schema.org/ItemList">
                            @foreach($footerMenu as $item)
                            <li class="footer-nav__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ItemList">
                                <a class="footer-nav__link" href="{{ $item->url }}" itemprop="url">{{ $item->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <div class="footer__company">{{ Settings::get('footer_copy') }}</div>
            <a class="footer__policy" href="{{ route('policy') }}" target="_blank">Политика конфиденциальности</a>
        </div>
    </div>
</footer>
