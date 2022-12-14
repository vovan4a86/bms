<div class="newses__card">
    <div class="newses__preview">
        <a href="{{ $item->url }}" title="{{ $item->name }}">
            @if($item->image)
            <img class="newses__pic lazy"
                 src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                 data-src="{{ $item->thumb(2) }}" width="405" height="241">
            @else
                <img class="newses__pic"
                     src="{{ \Fanky\Admin\Models\News::NO_IMAGE }}" width="405" height="241" style="object-fit: contain;">
            @endif
        </a>
    </div>
    <div class="newses__body">
        <a class="newses__link" href="{{ $item->url }}">{{ $item->name }}</a>
        <div class="newses__date">{{ $item->dateFormat('d F Y') }}</div>
    </div>
</div>