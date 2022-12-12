<!-- homepage ? '' : 'section--inner'-->
<section class="section about-block {{ Request::url() === '/' ? '' : 'section--inner' }}">
    <div class="container">
        <div class="about-block__head">
            <h2 class="about-block__title section__title">О компании СтальСервис</h2>
            @if(Settings::get('about_text'))
                <div class="about-block__content">
                    {!! Settings::get('about_text') !!}
                </div>
            @endif
        </div>
        @if($feats = Settings::get('about_features'))
            <div class="about-block__features">
                @foreach($feats as $feat)
                    <div class="features-block">
                        <div class="features-block__icon lazy" data-bg="{{ Settings::fileSrc($feat['about_features_image']) }}"></div>
                        <div class="features-block__title">{!! $feat['about_features_title'] !!}</div>
                        <div class="features-block__text">{!! $feat['about_features_text'] !!}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(Request::url() === route('main'))
            @if($img = Settings::get('main_about_image'))
                <div class="about-block__company lazy" data-bg="{{ Settings::fileSrc($img) }}"></div>
            @endif
        @endif
    </div>
</section>
