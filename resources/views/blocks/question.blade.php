<section class="section question">
    <div class="container">
        <div class="question__container lazy" data-bg="/static/images/common/question-bg.jpg">
            <form class="question__form" action="{{ route('ajax.questions') }}" onsubmit="sendQuestion(this,event)">
                <h2 class="question__title section__title">У вас остались вопросы?</h2>
                <p>Не можете найти нужную позицию металопроката — мы подберём нужный товар</p>
                <a class="question__phone" href="tel:+{{ preg_replace('/[^\d]+/', '', Settings::get('questions_phone')) }}">{{ Settings::get('questions_phone') }}</a>
                <p>Либо заполните форму и мы вам перезвоним</p>
                <div class="question__row">
                    <input class="question__input input" type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    <button class="question__submit btn">
                        <span>Отправить</span>
                    </button>
                </div>
                <div class="question__policy">Нажимая кнопку «Отправить», вы подтверждаете свое согласие на
                    <a href="/_ajax-policy.html" data-fancybox data-type="ajax">обработку персональных данных</a>
                </div>
            </form>
        </div>
    </div>
</section>