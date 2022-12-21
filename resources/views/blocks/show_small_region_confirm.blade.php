<div class="header__city">
    <div class="cities" x-data="{ openCityDialog: true }">
        <button class="cities__current btn-reset"
                @click="openCityDialog = !openCityDialog"
                :aria-expanded="{{ session('city_alias') ? 'openCityDialog = false' : 'openCityDialog = true' }}" type="button">
            <svg width="13" height="18" viewBox="0 0 13 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.079 2.57317C8.55825 -0.191057 4.41126 -0.191057 1.89054 2.57317C-0.63018 5.3374 -0.63018 9.80269 1.89054 12.5669L6.48475 17.5L11.079 12.5244C13.6403 9.80269 13.6403 5.29487 11.079 2.57317ZM6.48475 9.41995C5.34636 9.41995 4.45191 8.48436 4.45191 7.29362C4.45191 6.10288 5.34636 5.16729 6.48475 5.16729C7.62314 5.16729 8.51759 6.10288 8.51759 7.29362C8.51759 8.48436 7.62314 9.41995 6.48475 9.41995Z"
                      fill="#48A8EA" />
            </svg>
            <span>{{ $current_city ?? 'Екатеринбург' }}</span>
        </button>
        <div class="cities__data" x-data="{ openCityList: false }">
            <div class="cities-dialog" x-show="openCityDialog" @click.away="openCityDialog = false" x-transition>
                <div class="cities-dialog__label">Ваш город {{ $current_city ?? 'Екатеринбург' }}?</div>
                <div class="cities-dialog__actions">
                    <button class="cities-dialog__btn cities-dialog__btn--confirm btn-reset" type="button" @click="openCityDialog = false">
                        <span>Да</span>
                    </button>
                    <button class="cities-dialog__btn cities-dialog__btn--alt btn-reset" type="button" @click="openCityList = !openCityList">
                        <span>Нет, другой</span>
                    </button>
                </div>
            </div>
            <div class="cities-list" x-show="openCityList" @click.away="openCityList = false" x-transition x-cloak>
                <div class="cities-list__top">
                    <label class="cities-list__label">
                        <input class="cities-list__input" type="text" name="search" placeholder="Поиск города" data-search-city>
                        <span class="cities-list__search"></span>
                    </label>
                </div>
                <div class="cities-list__content">
                    <ul class="cities-list__list list-reset">
                        @foreach($cities as $city)
                            <li class="cities-list__item" data-city="{{ $city->name }}">
                                <a class="cities-list__link" href="{{ url($city->alias) }}">{{ $city->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
