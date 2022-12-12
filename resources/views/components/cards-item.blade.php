<div class="cards__item">
    <a class="cards__title" href="{{ $item->url }}">{{ $item->name }}</a>
    <div class="cards__info">
        @if($image = $item->thumb(1))
            <img class="lazy" data-src="{{ $image }}" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="image" title="title" />
        @endif
        <div class="cards__params">
            @foreach($params as $param)
                <div class="cards__param">{{ $param->name }}
                    <span>{{ $param->value }}</span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="cards__action">
        <a class="btn btn--accent" href="{{ $item->url }}">Перейти к товару</a>
    </div>
</div>