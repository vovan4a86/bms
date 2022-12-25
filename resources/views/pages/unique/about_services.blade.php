<div class="s-services__container container">
    <div class="s-services__title">{{ Settings::get('services_title') }}</div>
    @if($services = Settings::get('services_list'))
        <div class="s-services__grid">
            @foreach($services as $item)
                <div class="s-services__item">
                    <div class="s-services__icon lazy" data-bg="{{ Settings::fileSrc($item['services_image']) }}"></div>
                    <div class="s-services__subtitle">{!! $item['services_title'] !!}</div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="s-services__alert">
        <span>{{ Settings::get('services_text') }}</span>
        <div class="s-services__decor lazy" data-bg="/static/images/common/ico_compiliant.svg"></div>
    </div>
</div>