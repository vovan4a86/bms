<div class="s-payments">
    <div class="s-payments__container container">
        <div class="s-payments__title">Оплата и доставка</div>
        @if($items = Settings::get('cat_pd'))
            <div class="s-payments__grid">
                @foreach($items as $item)
                    <div class="s-payments__item {{ $item['cat_pd_style'] == 'wide' ? 's-payments__item--wide' : null }}">
                        <div class="payment-card">
                            <div class="payment-card__badge">{{ $item['cat_pd_type'] }}</div>
                            <div class="payment-card__info">
                                <div class="payment-card__icon lazy"
                                     data-bg="{{ Settings::fileSrc($item['cat_pd_icon']) }}"></div>
                                <div class="payment-card__data">
                                    <div class="payment-card__title">{{ $item['cat_pd_title'] }}</div>
                                    <div class="payment-card__text">{{ $item['cat_pd_text'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="s-payments__action">
            <a class="button button--primary" href="javascript:void(0)" title="Подробнее">
                <span>Подробнее</span>
                <svg width="20" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 5 13 .959V9.04L20 5ZM0 5.7h13.7V4.3H0v1.4Z" fill="#fff"/>
                </svg>
            </a>
        </div>
    </div>
</div>
