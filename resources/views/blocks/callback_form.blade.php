<section class="b-callback">
    <div class="b-callback__container container">
        <div class="b-callback__view">
            <div class="b-callback__content">
                <div class="b-callback__title">{{ Settings::get('cb_title') ?? 'см. настройки' }}</div>
                <div class="b-callback__subtitle">{{ Settings::get('cb_text') ?? 'см. настройки' }}</div>
                <div class="b-callback__decor lazy" data-bg="/static/images/common/callback-decor.svg"></div>
                <form class="b-callback__grid" action="{{ route('ajax.callback') }}"
                    onclick="sendCallback(this,event)">
                    @csrf
                    <div class="b-callback__item">
                        <div class="field field--white">
                            <input class="field__input" type="text" name="name" required>
                            <span class="field__highlight"></span>
                            <span class="field__bar"></span>
                            <label class="field__label">имя</label>
                        </div>
                    </div>
                    <div class="b-callback__item">
                        <div class="field field--white">
                            <input class="field__input" type="tel" name="phone" required>
                            <span class="field__highlight"></span>
                            <span class="field__bar"></span>
                            <label class="field__label">телефон</label>
                        </div>
                    </div>
                    <div class="b-callback__item">
                        <label class="checkbox checkbox--accent">
                            <input class="checkbox__input" type="checkbox" checked required>
                            <span class="checkbox__box"></span>
                            <span class="checkbox__policy">Даю согласие на обработку персональных данных.
											<a href="{{ route('policy') }}" target="_blank">Пользовательское соглашение</a>
										</span>
                        </label>
                    </div>
                    <div class="b-callback__item">
                        <button class="b-callback__submit submit submit--white btn-reset" name="submit">
                            <span>Заказать звонок</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="b-callback__pic lazy" data-bg="{{ Settings::fileSrc(Settings::get('cb_image')) }}"></div>
        </div>
    </div>
</section>
