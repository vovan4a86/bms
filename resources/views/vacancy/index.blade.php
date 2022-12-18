@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        @include('blocks.page_head')
        <section class="s-vacancy" x-data="{ city: 'Москва' }">
            <div class="s-vacancy__container container">
                @if($cities)
                    <div class="s-vacancy__top">
                        <nav class="page-nav">
                            <ul class="page-nav__list list-reset">
                                @foreach($cities as $city)
                                    <li class="page-nav__item" @click="city = '{{ $city->name }}'">
                                        <span :class="city == '{{ $city->name }}' &amp;&amp; 'is-active'">{{ $city->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                <div class="s-vacancy__content">
                    @foreach($cities as $city)
                    <div class="s-vacancy__view" x-show="city == '{{ $city->name }}'" x-transition.duration.250ms>
                        <div class="data-list" x-data="{ view: 0 }">
                            @foreach($city->vacancies as $vacancy)
                                <div class="data-list__item">
                                <div class="data-list__head" @click="view == {{ $loop->iteration }} ? view = 0 : view = {{ $loop->iteration }}">
                                    <div class="data-list__name">
                                        <div class="data-list__label">Должность</div>
                                        <div class="data-list__title">{{ $vacancy->name }}</div>
                                    </div>
                                    <div class="data-list__info">
                                        <div class="data-list__label">Заработная плата</div>
                                        <div class="data-list__title">от {{ $vacancy->priceFormat }} руб.</div>
                                    </div>
                                    <div class="data-list__icon" :class="view == {{ $loop->iteration }} &amp;&amp; 'is-active'">
                                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.875 16.25L13 8.125L21.125 16.25" stroke="currentColor"
                                                  stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                    </div>
                                </div>
                                <div class="data-list__body" x-show="view == {{ $loop->iteration }}" x-transition.duration.150ms>
                                    <div class="data-list__list">
                                      {!! $vacancy->text !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                    <div class="s-vacancy__top">
                        <h5>Вакансии отсутствуют</h5>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
