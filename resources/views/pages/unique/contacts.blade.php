@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section contacts {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container contacts__container">
                <h2 class="section__title section__title--inner">Контакты</h2>
                <div class="contacts__tabs tab" data-map-tabs>
                    <div class="tab__nav">
                        <div class="tab__link is-active" data-open="warehouse">Склад</div>
                        <div class="tab__link" data-open="office">Наш офис</div>
                    </div>
                    <div class="tab__views">
                        <div class="tab__view is-active" data-view="warehouse">
                            <div class="contacts__data">
                                @if(Settings::get('ware_addr'))
                                    <dl class="contacts__info">
                                        <dt>Адрес</dt>
                                        <dd>{{ Settings::get('ware_index') ? Settings::get('ware_index').', ' : '' }}{{ Settings::get('ware_addr') }}</dd>
                                    </dl>
                                @endif
                                @if(Settings::get('ware_email'))
                                    <dl class="contacts__info">
                                        <dt>Электронная почта</dt>
                                        <dd>
                                            <a href="mailto:{{ Settings::get('ware_email') }}">{{ Settings::get('ware_email') }}</a>
                                        </dd>
                                    </dl>
                                @endif
                                @if(Settings::get('ware_phone'))
                                    <dl class="contacts__info">
                                        <dt>Телефон</dt>
                                        <dd>
                                            <a href="tel:+{{ preg_replace('/[^\d]+/', '', Settings::get('ware_phone')) }}">{{ Settings::get('ware_phone') }}</a>
                                        </dd>
                                    </dl>
                                @endif
                            </div>
                            <div class="contacts__action">
                                <button class="btn btn--content" type="button" data-src="#callback" data-popup
                                        aria-label="Заказать звонок">
                                    <span>Заказать звонок</span>
                                </button>
                            </div>
                            <div class="contacts__map" id="warehouse" data-map
                                 data-lat="{{ Settings::get('ware_lat') }}"
                                 data-long="{{ Settings::get('ware_long') }}"
                                 data-hint="{{ Settings::get('ware_addr') }}"></div>
                        </div>
                        <div class="tab__view" data-view="office">
                            <div class="contacts__data">
                                @if(Settings::get('office_addr'))
                                    <dl class="contacts__info">
                                        <dt>Адрес</dt>
                                        <dd>{{ Settings::get('office_index') ? Settings::get('office_index').', ' : '' }}{{ Settings::get('office_addr') }}</dd>
                                    </dl>
                                @endif
                                @if(Settings::get('office_email'))
                                    <dl class="contacts__info">
                                        <dt>Электронная почта</dt>
                                        <dd>
                                            <a href="mailto:{{ Settings::get('office_email') }}">{{ Settings::get('office_email') }}</a>
                                        </dd>
                                    </dl>
                                @endif
                                @if(Settings::get('office_phone'))
                                    <dl class="contacts__info">
                                        <dt>Телефон</dt>
                                        <dd>
                                            <a href="tel:+{{ preg_replace('/[^\d]+/', '', Settings::get('office_phone')) }}">{{ Settings::get('office_phone') }}</a>
                                        </dd>
                                    </dl>
                                @endif
                            </div>
                            <div class="contacts__action">
                                <button class="btn btn--content" type="button" data-src="#callback" data-popup
                                        aria-label="Заказать звонок">
                                    <span>Заказать звонок</span>
                                </button>
                            </div>
                            <div class="contacts__map" id="office" data-map data-lat="{{ Settings::get('office_lat') }}" data-long="{{ Settings::get('office_long') }}"
                                 data-hint="{{ Settings::get('office_addr') }}"></div>
                        </div>
                    </div>
                </div>
                <form class="contacts__form sending" action="{{ route('ajax.contact-us') }}" onsubmit="sendContactUs(this,event)">
                    <div class="sending__content">
                        <div class="sending__title">Свяжитесь с нами</div>
                        <div class="sending__data">
                            <div class="sending__fields">
                                <label class="sending__label">Имя или Название организации
                                    <span>*</span>
                                    <input class="sending__input input" type="text" name="name" placeholder="Ваше имя"
                                           required>
                                </label>
                                <label class="sending__label">Телефон
                                    <span>*</span>
                                    <input class="sending__input input" type="tel" name="phone"
                                           placeholder="+7 (___) ___-__-__" required>
                                </label>
                            </div>
                            <div class="sending__field">
                                <label class="sending__label">Сообщение
                                    <span>*</span>
                                    <textarea class="sending__input input textarea" rows="3" name="text"
                                              placeholder="Напишите сообщение" required></textarea>
                                </label>
                            </div>
                        </div>
                        <div class="sending__action">
                            <button class="btn">
                                <span>Отправить</span>
                            </button>
                            <label class="checkbox">
                                <input class="checkbox__input" type="checkbox" name="policy" checked required>
                                <span class="checkbox__box"></span>
                                <span class="checkbox__policy">Согласен на
										<a href="/_ajax-policy.html" data-fancybox data-type="ajax">обработку персональных данных</a>
									</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

@stop