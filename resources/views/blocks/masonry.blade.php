@if($images = Settings::get('about_gallery'))
@php
    $chunk = array_chunk($images, 5);
    $order = [2,3,4,5,1]
@endphp
<div class="masonry">
    <div class="container masonry__container">
        @foreach($chunk as $arr => $items)
            @if($loop->iteration % 2 !== 0)
                <div class="masonry__row">
                    @foreach($items as $arr_num => $value)
                    <a class="masonry__link masonry__link--{{ $loop->iteration }}" href="{{ Settings::fileSrc($value['about_gallery_image']) }}" data-fancybox="about-gallery" data-caption="{{ $value['about_gallery_text'] }}">
                        <img class="masonry__img lazy" src="/" data-src="{{ Settings::fileSrc($value['about_gallery_image']) }}" alt="alt">
                    </a>
                    @endforeach
                </div>
            @else
                <div class="masonry__row">
                    @foreach($items as $arr_num => $value)
                    <a class="masonry__link masonry__link--{{ $order[$loop->index] }}" href="{{ Settings::fileSrc($value['about_gallery_image']) }}" data-fancybox="about-gallery" data-caption="{{ Settings::fileSrc($value['about_gallery_text']) }}">
                        <img class="masonry__img lazy" src="/" data-src="{{ Settings::fileSrc($value['about_gallery_image']) }}" alt="alt">
                    </a>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif