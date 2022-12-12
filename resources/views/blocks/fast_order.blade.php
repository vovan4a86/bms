<form class="hero__form form-hero" action="{{ route('ajax.fast-request') }}" onsubmit="sendFastRequest(this,event)">
    <div class="form-hero__title">Быстрый заказ</div>
    <div class="form-hero__fields">
        <label class="form-hero__label">Имя или Название организации
            <span>*</span>
            <input class="form-hero__input" type="text" name="name" placeholder="Ваше имя" required>
        </label>
        <label class="form-hero__label">Ваш телефон
            <span>*</span>
            <input class="form-hero__input" type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
        </label>
    </div>
    <button class="form-hero__submit btn">
        <span>Оставить заявку</span>
    </button>
    <div class="form-hero__policy">Нажимая кнопку «Отправить», вы подтверждаете свое согласие на
        <a href="/_ajax-policy.html" data-fancybox data-type="ajax">обработку персональных данных</a>
    </div>
</form>