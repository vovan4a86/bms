<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <title>Выберите регион</title>
    <link rel="stylesheet" type="text/css" href="/static/css/styles.css">
</head>

<body class="cities-page">
<div class="container cities-page__container data-cur_url="{{ $curUrl }}">
<div class="cities-page__title">Выберите регион:</div>
<div class="cities-page__current">
    <a class="cities-page__link" href="{{ route('default', ['contacts']) }}" }}>Россия</a>
</div>
<div class="cities-page__content">
    @foreach($cities as $letter => $letterCities)
        <ul class="cities-page__list">
            <li class="cities-page__label">{{ $letter }}</li>
            @foreach($letterCities as $letterCity)
                <li>
                    <a class="cities-page__link"
                       data-id="{{ $letterCity->id }}"
                       href="{{ url($letterCity->alias) }}"
                       rel="nofollow">
                        {{ $letterCity->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</div>
</div>
</body>

</html>