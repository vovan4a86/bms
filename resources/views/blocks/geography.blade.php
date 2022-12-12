<section class="section geography">
    <div class="container">
        <div class="geography__area lazy" data-bg="/static/images/common/map.svg">
            @if($states = Settings::get('state_south'))
                <div class="area-pin area-pin--south">
                    <div class="area-pin__label">Южный ФО</div>
                    <ul class="area-pin__list list-reset">
                        @foreach($states as $state)
                            <li class="area-pin__item">{{ $state }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($states = Settings::get('state_center'))
                <div class="area-pin area-pin--center">
                    <div class="area-pin__label">Центральный ФО</div>
                    <ul class="area-pin__list list-reset">
                        @foreach($states as $state)
                            <li class="area-pin__item">{{ $state }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($states = Settings::get('state_privol'))
                <div class="area-pin area-pin--privol">
                    <div class="area-pin__label">Приволжский ФО</div>
                    <ul class="area-pin__list list-reset">
                        @foreach($states as $state)
                            <li class="area-pin__item">{{ $state }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($states = Settings::get('state_nord'))
                <div class="area-pin area-pin--nord">
                    <div class="area-pin__label">Северо-Западный ФО</div>
                    <ul class="area-pin__list list-reset">
                        @foreach($states as $state)
                            <li class="area-pin__item">{{ $state }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($states = Settings::get('state_ural'))
                <div class="area-pin area-pin--ural">
                <div class="area-pin__label">Уральский ФО</div>
                <ul class="area-pin__list list-reset">
                    @foreach($states as $state)
                        <li class="area-pin__item">{{ $state }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if($states = Settings::get('state_sibir'))
                <div class="area-pin area-pin--sibir">
                    <div class="area-pin__label">Сибирский ФО</div>
                    <ul class="area-pin__list list-reset">
                        @foreach($states as $state)
                            <li class="area-pin__item">{{ $state }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($states = Settings::get('state_vostok'))

                <div class="area-pin area-pin--vostok">
                <div class="area-pin__label">Дальневосточный ФО</div>
                <ul class="area-pin__list list-reset">
                    @foreach($states as $state)
                        <li class="area-pin__item">{{ $state }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="geography__row">
                <div class="geography__head">
                    <h2 class="geography__title section__title">{{ Settings::get('geo_title') ? : '' }}</h2>
                    <p>{{ Settings::get('geo_subtitle') ? : '' }}</p>
                </div>
                <div class="geography__description">
                    {{ Settings::get('geo_text') ? : '' }}
                </div>
            </div>
        </div>
    </div>
    <div class="container geography__footer">
        <a class="btn" href="{{ url('/contacts') }}">
            <span>Контакты</span>
            <svg class="svg-sprite-icon icon-arrow">
                <use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
            </svg>
        </a>
    </div>
</section>
