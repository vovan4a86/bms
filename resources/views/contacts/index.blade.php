@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- headerIsWhite ? '' : 'page-head--dark'-->
        <div class="page-head page-head--dark">
            <div class="page-head__container container">
                <div class="page-head__content">
                    <div class="page-head__title">Контакты</div>
                    <div class="page-head__text"></div>
                </div>
            </div>
        </div>
        <section class="contacts">
            <div class="contacts__container container">
                <div class="contacts__body">
                    @if($top_contacts = Settings::get('contact_list'))
                        <div class="contacts__item">
                            <div class="contacts__staff">
                                @foreach($top_contacts as $item)
                                    <div class="staff">
                                        <div class="staff__name">{{ $item['contact_list_title'] }}</div>
                                        <div class="staff__grid">
                                            @if($item['contact_list_phone'])
                                                <div class="staff__col">
                                                    <a class="staff__link"
                                                       href="tel:{{ preg_replace('/[^\d+]/', '', $item['contact_list_phone']) }}"
                                                       title="Позвонить">
                                                <span class="staff__icon lazy"
                                                      data-bg="static/images/common/ico_phone.svg"></span>
                                                        <span class="staff__label">{{ $item['contact_list_phone'] }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if($item['contact_list_email'])
                                                <div class="staff__col">
                                                    <a class="staff__link"
                                                       href="mailto:{{ $item['contact_list_email'] }}"
                                                       title="Написать письмо">
                                                <span class="staff__icon lazy"
                                                      data-bg="static/images/common/ico_email.svg"></span>
                                                        <span class="staff__label">{{ $item['contact_list_email'] }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if($item['contact_list_whatsapp'])
                                                <div class="staff__col">
                                                    <a class="staff__link"
                                                       href="https://wa.me/{{ preg_replace('/[^\d]+/', '', $item['contact_list_whatsapp']) }}"
                                                       title="Написать в Whatsapp">
                                                <span class="staff__icon lazy"
                                                      data-bg="static/images/common/ico_whatsapp.svg"></span>
                                                        <span class="staff__label">{{ $item['contact_list_whatsapp'] }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if($item['contact_list_skype'])
                                                <div class="staff__col">
                                                    <a class="staff__link"
                                                       href="skype:{{ $item['contact_list_skype'] }}?chat"
                                                       title="Написать в Skype">
                                                <span class="staff__icon lazy"
                                                      data-bg="static/images/common/ico_skype.svg"></span>
                                                        <span class="staff__label">{{ $item['contact_list_skype'] }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                            @if($item['contact_list_telegram'])
                                                <div class="staff__col">
                                                    <a class="staff__link"
                                                       href="https://t.me/{{ preg_replace('/[^\d]+/', '', $item['contact_list_telegram']) }}"
                                                       title="Написать в Telegram">
                                                <span class="staff__icon lazy"
                                                      data-bg="static/images/common/ico_tg.svg"></span>
                                                        <span class="staff__label">{{ $item['contact_list_telegram'] }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="staff__action">
                                            <button class="staff__popup btn-reset" type="button" data-question
                                                    data-src="#question" aria-label="Написать">
                                                <span>Написать</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(count($contacts))
                        @foreach($contacts as $contact)
                            <div class="contacts__item">
                                <div class="contacts__title">{{ $contact->city_name }}</div>
                                <div class="contacts__info">
                                    <div class="contacts__data">
                                        <div class="contacts__subtitle">
                                            {!! $contact->title !!}
                                        </div>
                                        <div class="contacts__list">
                                            @if($contact->address)
                                                <div class="contacts__list-row">
                                                    <div class="contacts__list-icon lazy"
                                                         data-bg="static/images/common/ico_pin--blue.svg"></div>
                                                    <div class="contacts__list-label">{{ $contact->address }}</div>
                                                </div>
                                            @endif
                                            @if($contact->phone1)
                                                <div class="contacts__list-row">
                                                    <div class="contacts__list-icon lazy"
                                                         data-bg="static/images/common/ico_phone--blue.svg"></div>
                                                    <div class="contacts__list-label">
                                                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $contact->phone1) }}">{{ $contact->phone1 }}</a>
                                                        &nbsp;— {{ $contact->phone1_comment }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if($contact->phone2)
                                                <div class="contacts__list-row">
                                                    <div class="contacts__list-icon lazy"
                                                         data-bg="static/images/common/ico_phone--blue.svg"></div>
                                                    <div class="contacts__list-label">
                                                        <a href="tel:{{ preg_replace('/[^\d+]/', '', $contact->phone2) }}">{{ $contact->phone2 }}</a>&nbsp;—
                                                        {{ $contact->phone2_comment }}
                                                    </div>
                                                </div>
                                            @endif
                                            @if($contact->email)
                                                <div class="contacts__list-row">
                                                    <div class="contacts__list-icon lazy"
                                                         data-bg="static/images/common/ico_email--blue.svg"></div>
                                                    <div class="contacts__list-label">
                                                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="contacts__list-row">
                                                <div class="contacts__list-icon lazy"
                                                     data-bg="static/images/common/ico_time--blue.svg"></div>
                                                <div class="contacts__list-label">
                                                    <span>пн-пт</span>&nbsp;9:00-18:00
                                                    <br/>
                                                    <span>пт до</span>&nbsp;17:00
                                                </div>
                                            </div>
                                        </div>
                                        @if($contact->whatsapp || $contact->telegram || $contact->skype )
                                            <div class="contacts__write">
                                                <div class="contacts__write-label">Написать продавцу:</div>
                                                <div class="contacts__write-links">
                                                    @if($contact->whatsapp)
                                                        <a class="contacts__write-link lazy"
                                                           href="https://wa.me/{{ preg_replace('/[^\d]+/', '', $contact->whatsapp) }}"
                                                           data-bg="static/images/common/ico_whatsapp.svg"
                                                           title="Whatsapp"></a>
                                                    @endif
                                                    @if($contact->telegram)
                                                        <a class="contacts__write-link lazy"
                                                           href="https://t.me/{{ preg_replace('/[^\d]+/', '', $contact->telegram) }}"
                                                           data-bg="static/images/common/ico_tg.svg"
                                                           title="Telegram"></a>
                                                    @endif
                                                    @if($contact->skype)
                                                        <a class="contacts__write-link lazy"
                                                           href="skype:{{ $contact->skype }}?chat"
                                                           data-bg="static/images/common/ico_sk.svg" title="Skype"></a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="contacts__map" id="map_{{ $contact->id }}" data-map data-lat="{{ $contact->lat }}"
                                         data-long="{{ $contact->long }}"
                                         data-hint="{{ $contact->address }}"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="contacts__item">
                        <div class="contacts__grid">
                            @if(count($cityLinks))
                            <div class="contacts__cities">
                                <div class="contacts__title">Наши филиалы в городах</div>
                                <ul class="contacts__cities-list list-reset">
                                    @foreach($cityLinks as $link)
                                    <li>
                                        <a href="{{ url($link->alias) }}">{{ $link->name }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="contacts__form">
                                <form class="accent-form" action="{{ route('ajax.contact-us') }}"
                                      onsubmit="sendContactUs(this, event)">
                                    <div class="accent-form__title">Свяжитесь с нами</div>
                                    <div class="accent-form__subtitle">Мы готовы ответить на все ваши вопросы. Напишите
                                        нам, и мы свяжемся с вами в ближайшее время
                                    </div>
                                    <div class="accent-form__row">
                                        <div class="field field--white">
                                            <input class="field__input" type="text" name="name" required>
                                            <span class="field__highlight"></span>
                                            <span class="field__bar"></span>
                                            <label class="field__label">имя</label>
                                        </div>
                                        <div class="field field--white">
                                            <input class="field__input" type="tel" name="phone" required>
                                            <span class="field__highlight"></span>
                                            <span class="field__bar"></span>
                                            <label class="field__label">телефон</label>
                                        </div>
                                    </div>
                                    <div class="accent-form__text">
                                        <div class="field field--white">
                                            <input class="field__input" type="text" name="text" required>
                                            <span class="field__highlight"></span>
                                            <span class="field__bar"></span>
                                            <label class="field__label">Ваше сообщение</label>
                                        </div>
                                    </div>
                                    <div class="accent-form__action">
                                        <label class="checkbox checkbox--accent">
                                            <input class="checkbox__input" type="checkbox" checked required>
                                            <span class="checkbox__box"></span>
                                            <span class="checkbox__policy">Даю согласие на обработку персональных данных.
													<a href="{{ route('policy') }}" target="_blank">Пользовательское соглашение</a>
												</span>
                                        </label>
                                        <button class="accent-form__submit submit submit--white btn-reset"
                                                name="submit">
                                            <span>Отправить</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@stop