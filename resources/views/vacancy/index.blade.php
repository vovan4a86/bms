@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        @include('blocks.page_head')
        <section class="s-vacancy" x-data="{ city: 'Москва' }">
            <div class="s-vacancy__container container">
                <div class="s-vacancy__top">
                    <nav class="page-nav">
                        <ul class="page-nav__list list-reset">
                            <li class="page-nav__item" @click="city = 'Екатеринбург'">
                                <span :class="city == 'Екатеринбург' &amp;&amp; 'is-active'">Екатеринбург</span>
                            </li>
                            <li class="page-nav__item" @click="city = 'Москва'">
                                <span :class="city == 'Москва' &amp;&amp; 'is-active'">Москва</span>
                            </li>
                            <li class="page-nav__item" @click="city = 'Иркутск'">
                                <span :class="city == 'Иркутск' &amp;&amp; 'is-active'">Иркутск</span>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="s-vacancy__content">
                    <div class="s-vacancy__view" x-show="city == 'Екатеринбург'" x-transition.duration.250ms>
                        <div class="data-list" x-data="{ view: 0 }">
                            <!-- item-->
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 1 ? view = 0 : view = 1">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер по продажам чёрного металлопроката</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 1 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 1" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 2 ? view = 0 : view = 2">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер активных продаж</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 2 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 2" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 3 ? view = 0 : view = 3">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер по продажам нержавеющего и цветного металлопроката</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 3 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 3" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-vacancy__view" x-show="city == 'Москва'" x-transition.duration.250ms>
                        <div class="data-list" x-data="{ view: 0 }">
                            <!-- item-->
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 1 ? view = 0 : view = 1">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер по продажам чёрного металлопроката</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 1 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 1" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 2 ? view = 0 : view = 2">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер активных продаж</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 2 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 2" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 3 ? view = 0 : view = 3">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер по продажам нержавеющего и цветного металлопроката</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 3 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 3" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="s-vacancy__view" x-show="city == 'Иркутск'" x-transition.duration.250ms>
                        <div class="data-list" x-data="{ view: 0 }">
                            <!-- item-->
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 1 ? view = 0 : view = 1">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер по продажам чёрного металлопроката</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 1 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 1" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 2 ? view = 0 : view = 2">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер активных продаж</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 2 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 2" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="data-list__item">
                                <div class="data-list__head" @click="view == 3 ? view = 0 : view = 3">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">Менеджер по продажам нержавеющего и цветного металлопроката</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от 78 000 руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == 3 &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == 3" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Требования:</div>
                                            <ul class="list-reset">
                                                <li>Опыт работы в продажах не менее 2-х лет</li>
                                                <li>Опыт работы в металлоторговле приветствуется</li>
                                                <li>Знание специфики и конъюнктуры рынка торговли металлом желательно</li>
                                                <li>Знание техники продаж‚ тактик ведения переговоров на любом уровне‚ способность прогнозирования продаж</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Обязанности:</div>
                                            <ul class="list-reset">
                                                <li>Активные оптовые и розничные продажи‚ поиск клиентов</li>
                                                <li>Организация и проведение встреч с потенциальными клиентами</li>
                                                <li>Коммуникации с постоянными клиентами Компании</li>
                                                <li>Ведение переговоров и заключение договоров</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                        <div class="data-list__point">
                                            <div class="data-list__subtitle">Условия:</div>
                                            <ul class="list-reset">
                                                <li>Оформление по ТК РФ</li>
                                                <li>Испытательный срок 3 месяца</li>
                                                <li>Пятидневная рабочая неделя‚ график с 8.30 до 17.30</li>
                                                <li>Оплата труда: оклад + % от продаж (определяется по результатам собеседования)</li>
                                                <li>Документальная работа по оформлению и сопровождению сделок</li>
                                                <li>Работа с первичной документацией</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
