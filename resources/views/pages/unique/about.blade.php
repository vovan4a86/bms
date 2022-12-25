@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <section class="about">
            <div class="about__lead">
                <div class="about__container container">
                    <div class="about__lead-grid">
                        <div class="about__lead-col">
                            <div class="about__title">{{ Settings::get('about_title') }}</div>
                            <div class="about__text">{{ Settings::get('about_text') }}</div>
                            @if($feats = Settings::get('about_features'))
                                <div class="lead-features">
                                    @foreach($feats as $feat)
                                        <div class="lead-features__column">
                                            <div class="lead-features__decor">
                                                <div class="lead-features__icon lazy"
                                                     data-bg="{{ Settings::fileSrc($feat['about_features_image']) }}">
                                                </div>
                                            </div>
                                            <div class="lead-features__label">{{ $feat['about_features_title'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="about__lead-img lazy" data-bg="{{ $about_image }}"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="s-trade">
            <div class="s-trade__container container">
                <div class="s-trade__row">
                    <div class="title">Что мы продаём</div>
                    <a class="link-arrow" href="{{ route('catalog.index') }}" title="В каталог">
                        <span>В каталог</span>
                        <svg width="20" height="10" viewBox="0 0 20 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 5L13 0.958548V9.04145L20 5ZM0 5.7H13.7V4.3H0V5.7Z" fill="currentColor"/>
                        </svg>
                    </a>
                </div>
                <div class="s-trade__grid">
                    <div class="s-trade__col s-trade__col--wide">
                        <a class="card-link" href="javascript:void(0)" title="Трубы">
                            <span class="card-link__title">Трубы</span>
                            <span class="card-link__text">горяче- и холоднокатаные, квадратные и прямоугольные, ВГП, электросварные и любые другие</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-1.png"
                                 width="305" height="270" alt=""/>
                        </a>
                    </div>
                    <div class="s-trade__col s-trade__col--medium">
                        <a class="card-link" href="javascript:void(0)" title="Сортовый прокат">
                            <span class="card-link__title">Сортовый прокат</span>
                            <span class="card-link__text">арматура, шестигранник, круг, квадрат</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-4.png"
                                 width="318" height="256" alt=""/>
                        </a>
                    </div>
                    <div class="s-trade__col">
                        <a class="card-link" href="javascript:void(0)" title="Листовой прокат">
                            <span class="card-link__title">Листовой прокат</span>
                            <span class="card-link__text">ПВЛ, нержавеющие, оцинкованные и другие</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-9.png"
                                 width="270" height="167" alt=""/>
                        </a>
                    </div>
                    <div class="s-trade__col">
                        <a class="card-link" href="javascript:void(0)" title="Фасонные изделия">
                            <span class="card-link__title">Фасонные изделия</span>
                            <span class="card-link__text"></span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-12.png"
                                 width="405" height="270" alt=""/>
                        </a>
                    </div>
                    <div class="s-trade__col">
                        <a class="card-link" href="javascript:void(0)" title="Сантехарматура">
                            <span class="card-link__title">Сантехарматура</span>
                            <span class="card-link__text"></span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-5.png"
                                 width="290" height="234" alt=""/>
                        </a>
                    </div>
                    <div class="s-trade__col">
                        <a class="card-link" href="javascript:void(0)" title="Цветной металлопрокат">
                            <span class="card-link__title">Цветной металлопрокат</span>
                            <span class="card-link__text">алюминий, медь, латунь и титан</span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-13.png"
                                 width="405" height="270" alt=""/>
                        </a>
                    </div>
                    <div class="s-trade__col">
                        <a class="card-link" href="javascript:void(0)" title="Метизы">
                            <span class="card-link__title">Метизы</span>
                            <span class="card-link__text"></span>
                            <img class="card-link__pic lazy" src="/" data-src="/static/images/common/p-cat-10.png"
                                 width="405" height="270" alt=""/>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="s-services s-services--normal lazy" data-bg="{{ Settings::fileSrc(Settings::get('about_bg')) }}">
            @include('pages.unique.about_services')
        </section>
        <section class="s-top">
            <div class="s-top__container container">
                <div class="s-top__grid">
                    <div class="s-top__body">
                        <div class="s-top__title">{{ Settings::get('top_title') }}</div>
                        @if($top = Settings::get('top_list'))
                            <div class="s-top__list">
                                @foreach($top as $item)
                                    <div class="s-top__item">
                                        <div class="s-top__row">
                                            <div class="s-top__icon lazy" data-bg="{{ Settings::fileSrc($item['top_list_image']) }}"></div>
                                            <div class="s-top__subtitle">{{ $item['top_list_title'] }}</div>
                                        </div>
                                        <div class="s-top__text">{{ $item['top_list_text'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="s-top__pic lazy" data-bg="{{ Settings::fileSrc(Settings::get('top_image')) }}"></div>
                </div>
            </div>
        </section>
        <div class="s-payments">
            <div class="s-payments__container container">
                <div class="s-payments__title">Оплата и доставка</div>
                <div class="s-payments__grid">
                    <div class="s-payments__item">
                        <div class="payment-card">
                            <div class="payment-card__badge">Оплата</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy" data-bg="/static/images/common/ico_cash.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Наличный расчет</div>
                                    <div class="payment-card__text">На месте при получении, либо по предоплате.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-payments__item">
                        <div class="payment-card">
                            <div class="payment-card__badge">Оплата</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy" data-bg="/static/images/common/ico_card.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Безналичный расчет</div>
                                    <div class="payment-card__text">100 % предоплата</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-payments__item">
                        <div class="payment-card">
                            <div class="payment-card__badge">Доставка</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy" data-bg="/static/images/common/ico_house.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Самовывоз</div>
                                    <div class="payment-card__text">г. Екатеринбург, ул. Совхозная 20А</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-payments__item">
                        <div class="payment-card">
                            <div class="payment-card__badge">Доставка</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy" data-bg="/static/images/common/ico_truck.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Доставка почтой</div>
                                    <div class="payment-card__text">Россия + страны СНГ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-payments__item s-payments__item--wide">
                        <div class="payment-card">
                            <div class="payment-card__badge">Доставка</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy"
                                     data-bg="/static/images/common/ico_delivery-man.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Доставка курьером</div>
                                    <div class="payment-card__text">Осуществляем доставку в любые регионы</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-payments__item s-payments__item--wide">
                        <div class="payment-card">
                            <div class="payment-card__badge">Доставка</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy"
                                     data-bg="/static/images/common/ico_delivery-car.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Доставка транспортной компанией</div>
                                    <div class="payment-card__text">Россия + страны СНГ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-payments__item s-payments__item--wide">
                        <div class="payment-card">
                            <div class="payment-card__badge">Доставка</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy"
                                     data-bg="/static/images/common/ico_warehouse.svg"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">Доставка нашим автопарком</div>
                                    <div class="payment-card__text">Екатеринбург + Свердловская, Челябинская, Пермская и
                                        Курганская области
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="s-payments__action">
                    <a class="button button--primary" href="javascript:void(0)" title="Подробнее">
                        <span>Подробнее</span>
                        <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <section class="s-gallery">
            <div class="s-gallery__container container">
                <div class="title">Фото-галерея</div>
                <div class="s-gallery__grid">
                    <a class="s-gallery__card" href="/static/images/common/gal-1.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-1.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-2.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-2.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-3.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-3.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-4.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-4.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-5.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-5.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-6.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-6.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-7.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-7.jpg" alt="">
                    </a>
                    <a class="s-gallery__card" href="/static/images/common/gal-8.jpg" data-fancybox="gallery">
                        <img class="s-gallery__pic lazy"
                             src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                             data-src="/static/images/common/gal-8.jpg" alt="">
                    </a>
                </div>
            </div>
        </section>
    </main>
@stop
