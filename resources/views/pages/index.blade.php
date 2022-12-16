@extends('template')
@section('content')
{{--        @include('blocks.main_slider')--}}
<div class="scroller swiper" data-page-scroller>
    <div class="scroller__wrapper swiper-wrapper">
        <!-- data-background="dark"-->
        <section class="hero swiper-slide" data-background="dark">
            <div class="hero__slider swiper" data-main-slider>
                <div class="hero__wrapper swiper-wrapper">
                    <div class="hero__slide swiper-slide">
                        <div class="hero__content container">
                            <div class="hero__title">Комплексное снабжение предприятий, розничная торговля металлопроката</div>
                            <div class="hero__text">У нас вы найдете практически весь перечень изделий из чёрного, цветного и нержавеющего металлов</div>
                            <div class="hero__action">
                                <a class="button button--primary" href="javascript:void(0)" title="Перейти в каталог">
                                    <span>В каталог</span>
                                    <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff" />
                                    </svg>
                                </a>
                            </div>
                            <div class="hero__features">
                                <div class="features-list">
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Качество</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Компетентность
                                            <br />и профессионализм</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Оперативные
                                            <br />отгрузки</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Изготовление изделий
                                            <br />по вашим размерам</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero__background">
                            <video src="static/data/video.mp4" poster="/static/images/common/poster.jpg" autoplay muted loop playsinline></video>
                        </div>
                    </div>
                    <div class="hero__slide swiper-slide">
                        <div class="hero__content container">
                            <div class="hero__title">Комплексное снабжение предприятий</div>
                            <div class="hero__text">У нас вы найдете практически весь перечень изделий из чёрного, цветного и нержавеющего металлов</div>
                            <div class="hero__action">
                                <a class="button button--primary" href="javascript:void(0)" title="Перейти в каталог">
                                    <span>Нержавеющий прокат</span>
                                    <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff" />
                                    </svg>
                                </a>
                            </div>
                            <div class="hero__features">
                                <div class="features-list">
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Качество</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Изготовление изделий
                                            <br />по вашим размерам</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Оперативные
                                            <br />отгрузки</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero__background">
                            <picture>
                                <source srcset="/static/images/common/hero-slide.webp" type="image/webp">
                                <img class="swiper-lazy" src="/" data-src="/static/images/common/hero-slide.jpg" alt="">
                            </picture>
                        </div>
                    </div>
                    <div class="hero__slide swiper-slide">
                        <div class="hero__content container">
                            <div class="hero__title">Комплексное снабжение предприятий, розничная торговля металлопроката</div>
                            <div class="hero__text">У нас вы найдете практически весь перечень изделий из металлов</div>
                            <div class="hero__action">
                                <a class="button button--primary" href="javascript:void(0)" title="Перейти в каталог">
                                    <span>Трубный прокат</span>
                                    <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff" />
                                    </svg>
                                </a>
                            </div>
                            <div class="hero__features">
                                <div class="features-list">
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Качество</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Компетентность
                                            <br />и профессионализм</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Оперативные
                                            <br />отгрузки</div>
                                    </div>
                                    <div class="features-list__item">
                                        <div class="features-list__icon">
                                            <svg class="svg-sprite-icon icon-accepted">
                                                <use xlink:href="/static/images/sprite/symbol/sprite.svg#accepted"></use>
                                            </svg>
                                        </div>
                                        <div class="features-list__label">Изготовление изделий
                                            <br />по вашим размерам</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hero__background">
                            <picture>
                                <source srcset="/static/images/common/hero-slide-2.webp" type="image/webp">
                                <img class="swiper-lazy" src="/" data-src="/static/images/common/hero-slide-2.jpg" alt="">
                            </picture>
                        </div>
                    </div>
                    <div class="hero__pagination swiper-pagination"></div>
                    <div class="hero__nav">
                        <div class="slider-nav">
                            <button class="slider-nav__btn slider-nav__btn--left btn-reset" type="button" data-hero-prev>
                                <svg width="19" height="19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.868 3.58 5.948 9.5l5.92 5.92" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <button class="slider-nav__btn slider-nav__btn--right btn-reset" type="button" data-hero-next>
                                <svg width="19" height="19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="m7.132 3.58 5.92 5.92-5.92 5.92" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- data-background="light"-->
        <section class="s-catalog swiper-slide" data-background="light">
            <div class="s-catalog__container container">
                <div class="s-catalog__grid">
                    <div class="s-catalog__item">
                        <div class="discount-card lazy" data-bg="/static/images/common/discount-bg.png">
                            <img class="discount-card__pic lazy" src="/" data-src="/static/images/common/discount-pic.png" width="223" height="267" alt="">
                            <div class="discount-card__body">
                                <div class="discount-card__head">
                                    <div class="discount-card__title">Трубы профильные</div>
                                    <div class="discount-card__text">прямоугольное и квадратное сечение марка стали 09Г2С и др.</div>
                                </div>
                                <div class="discount-card__prices">
                                    <div class="discount-card__current-price">От&nbsp;
                                        <span data-end="₽">140</span>
                                    </div>
                                    <div class="discount-card__old-price" data-end="₽">185</div>
                                </div>
                                <div class="discount-card__link">
                                    <button class="button button--white button--small btn-reset">
                                        <span>В каталог</span>
                                        <svg width="21" height="10" viewBox="0 0 21 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.5 5L13.5 0.958548V9.04145L20.5 5ZM0.5 5.7H14.2V4.3H0.5V5.7Z" fill="currentColor" />
                                        </svg>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-catalog__item">
                        <a class="card-link" href="javascript:void(0)" title="Трубный прокат">
                            <span class="card-link__count">5 235 товаров</span>
                            <span class="card-link__title">Трубный прокат</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/cat-1.png" width="357" height="326" alt="" />
                        </a>
                    </div>
                    <div class="s-catalog__item">
                        <a class="card-link" href="javascript:void(0)" title="Цветной прокат">
                            <span class="card-link__count">13 375 товаров</span>
                            <span class="card-link__title">Цветной прокат</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/cat-2.png" width="359" height="326" alt="" />
                        </a>
                    </div>
                    <div class="s-catalog__item">
                        <a class="card-link" href="javascript:void(0)" title="Нержавеющий прокат">
                            <span class="card-link__count">6 166 товаров</span>
                            <span class="card-link__title">Нержавеющий прокат</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/cat-3.png" width="359" height="326" alt="" />
                        </a>
                    </div>
                    <div class="s-catalog__item">
                        <a class="card-link" href="javascript:void(0)" title="Сортовой прокат">
                            <span class="card-link__count">18 050 товаров</span>
                            <span class="card-link__title">Сортовой прокат</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/cat-4.png" width="405" height="326" alt="" />
                        </a>
                    </div>
                    <div class="s-catalog__item">
                        <a class="card-link" href="javascript:void(0)" title="Сантехарматура">
                            <span class="card-link__count">4 130 товаров</span>
                            <span class="card-link__title">Сантехарматура</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/cat-5.png" width="405" height="326" alt="" />
                        </a>
                    </div>
                    <div class="s-catalog__item">
                        <a class="card-link" href="javascript:void(0)" title="Поковки">
                            <span class="card-link__count">48 товаров</span>
                            <span class="card-link__title">Поковки</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/cat-6.png" width="405" height="326" alt="" />
                        </a>
                    </div>
                    <div class="s-catalog__item">
                        <a class="catalog-link lazy" data-bg="/static/images/common/catalog-bg.jpg" href="javascript:void(0)" title="Смотреть весь каталог">
                            <span class="catalog-link__title">Смотреть весь каталог</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="29" height="14" fill="none">
                                <path fill="currentColor" d="M29 7 17 .072v13.856L29 7ZM0 8.2h18.2V5.8H0v2.4Z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- data-background="dark"-->
        <section class="callback-block swiper-slide lazy" data-bg="/static/images/common/callback-bg.jpg" data-background="dark" data-swiper-parallax="-100%">
            <div class="callback-block__container container">
                <div class="callback-block__title">Нет времени на поиск в каталоге — Запроси Обратный звонок</div>
                <div class="callback-block__subtitle">Менеджер Александр подскажет по ассортименту и сделает лучшее предложение</div>
                <form class="callback-block__grid" action="#">
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
										<a href="javascript:void(0)" target="_blank">Пользовательское соглашение</a>
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
                <div class="s-about__content lazy" data-bg="/static/images/common/about-bg.jpg">
                    <div class="s-about__container container">
                        <div class="s-about__title">ООО «Бизнес-МС»</div>
                        <div class="s-about__subtitle">ООО "Бизнес МС" - компания профессионалов с опытом работы в области транспортной логистики и снабжения предприятий России и стран ближнего зарубежья металлопрокатом из черных нержавеющих и цветных металлов, а так же их сплавов.</div>
                        <div class="s-about__grid">
                            <div class="s-about__column">
                                <div class="s-about__decor">
                                    <div class="s-about__icon lazy" data-bg="/static/images/common/ico_wholesale.svg"></div>
                                </div>
                                <div class="s-about__label">Оптовый продавец</div>
                            </div>
                            <div class="s-about__column">
                                <div class="s-about__decor">
                                    <div class="s-about__icon lazy" data-bg="/static/images/common/ico_parcel.svg"></div>
                                </div>
                                <div class="s-about__label">Розничный продавец</div>
                            </div>
                            <div class="s-about__column">
                                <div class="s-about__decor">
                                    <div class="s-about__icon lazy" data-bg="/static/images/common/ico_hand.svg"></div>
                                </div>
                                <div class="s-about__label">Посредник (агент)</div>
                            </div>
                            <div class="s-about__column">
                                <div class="s-about__decor">
                                    <div class="s-about__icon lazy" data-bg="/static/images/common/ico_bending.svg"></div>
                                </div>
                                <div class="s-about__label">Услуги и сервис</div>
                            </div>
                        </div>
                        <div class="s-about__link">
                            <a class="button button--primary" href="javascript:void(0)">
                                <span>о компании</span>
                                <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="s-about__human lazy" data-bg="/static/images/common/human-bg.jpg"></div>
            </div>
        </section>
        <!-- data-background="dark"-->
        <section class="s-services swiper-slide lazy" data-bg="/static/images/common/services-bg.jpg" data-background="dark" data-swiper-parallax="-200%">
            <div class="s-services__container container">
                <div class="s-services__title">Для вашего удобства предлагаем следующие услуги:</div>
                <div class="s-services__grid">
                    <div class="s-services__item">
                        <div class="s-services__icon lazy" data-bg="/static/images/common/ico_laser.svg"></div>
                        <div class="s-services__subtitle">Обработку металла
                            <br />на высокоточных станках</div>
                    </div>
                    <div class="s-services__item">
                        <div class="s-services__icon lazy" data-bg="/static/images/common/ico_tank.svg"></div>
                        <div class="s-services__subtitle">Производство
                            <br />металлоконструкций</div>
                    </div>
                    <div class="s-services__item">
                        <div class="s-services__icon lazy" data-bg="/static/images/common/ico_training.svg"></div>
                        <div class="s-services__subtitle">Изготовление деталей
                            <br />по чертежам</div>
                    </div>
                    <div class="s-services__item">
                        <div class="s-services__icon lazy" data-bg="/static/images/common/ico_bending.svg"></div>
                        <div class="s-services__subtitle">Токарные
                            <br />работы</div>
                    </div>
                    <div class="s-services__item">
                        <div class="s-services__icon lazy" data-bg="/static/images/common/ico_delivery.svg"></div>
                        <div class="s-services__subtitle">Грузоперевозки,
                            <br />аренда</div>
                    </div>
                </div>
                <div class="s-services__alert">
                    <span>ВАЖНО! Все представленные услуги выполняются в условиях специализированных предприятий, а значит — качественно и профессионально</span>
                    <div class="s-services__decor lazy" data-bg="/static/images/common/ico_compiliant.svg"></div>
                </div>
            </div>
        </section>
        <!-- data-background="light"-->
        <section class="s-map swiper-slide" data-background="light">
            <div class="s-map__container container">
                <div class="s-map__title">География
                    <br />поставок</div>
                <div class="s-map__grid">
                    <div class="s-map__item">
                        <div class="s-map__head">
                            <div class="s-map__icon lazy" data-bg="/static/images/common/ico_protected.svg"></div>
                            <div class="s-map__subtitle">География поставок</div>
                        </div>
                        <div class="s-map__body">Продукция с гарантией завода-изготовителя подтверждается сертификатом</div>
                    </div>
                    <div class="s-map__item">
                        <div class="s-map__head">
                            <div class="s-map__icon lazy" data-bg="/static/images/common/ico_concrete.svg"></div>
                            <div class="s-map__subtitle">Любой объем</div>
                        </div>
                        <div class="s-map__body">1 кг или несколько вагонов - мы доставим все быстро и строго в оговоренный срок</div>
                    </div>
                    <div class="s-map__item">
                        <div class="s-map__head">
                            <div class="s-map__icon lazy" data-bg="/static/images/common/ico_operator.svg"></div>
                            <div class="s-map__subtitle">Профессиональный менеджмент</div>
                        </div>
                        <div class="s-map__body">Грамотная консультация, быстрый расчет, полное сопровождение заказа</div>
                    </div>
                    <div class="s-map__item">
                        <div class="s-map__head">
                            <div class="s-map__icon lazy" data-bg="/static/images/common/ico_distance.svg"></div>
                            <div class="s-map__subtitle">Доставка по России и странам СНГ</div>
                        </div>
                        <div class="s-map__body">Самовывоз, транспортные компании</div>
                    </div>
                    <div class="s-map__item">
                        <div class="s-map__head">
                            <div class="s-map__icon lazy" data-bg="/static/images/common/ico_offer.svg"></div>
                            <div class="s-map__subtitle">С нами выгодно</div>
                        </div>
                        <div class="s-map__body">Нашли продукцию дешевле? Предоставьте счет и получите приятную скидку</div>
                    </div>
                </div>
            </div>
            <img class="s-map__map lazy" src="/" data-src="/static/images/common/map.svg" width="1260" height="745" alt="">
        </section>
@stop
