<div class="popup" id="request-done" style="display:none; padding: 50px;">
    <div class="popup__complete">
        <div class="popup__complete-icon lazy" data-bg="/static/images/common/ico_done.svg"></div>
        <div class="popup__complete-label">Ваша заявка отправлена. Наши специалисты свяжутся с вами в ближайшее время. Спасибо.</div>
    </div>
</div>

<div class="popup" id="order-done" style="display:none">
    <div class="popup__complete">
        <div class="popup__complete-icon lazy" data-bg="/static/images/common/ico_box.svg"></div>
        <div class="popup__complete-label">Ваш заказ отправлен. Наши специалисты свяжутся с вами в ближайшее время. Спасибо.</div>
    </div>
</div>

<form class="popup" id="search" action="{{ route('search') }}" style="display:none">
    <div class="popup__search-field">
        <div class="field field--promo">
            <input class="field__input" type="search" name="q" value="{{ Request::get('q') }}" required>
            <span class="field__highlight"></span>
            <span class="field__bar"></span>
            <label class="field__label">Найти</label>
            <button class="btn-reset field__search" name="submit" aria-label="Найти"></button>
        </div>
    </div>
</form>

<form class="order-popup popup" id="order_tonn" action="{{ route('ajax.add-to-cart') }}"
      style="display:none" onsubmit="addToCartProductPopup(this, event)">
    <div class="order-popup__head">
        <div class="order-popup__title" data-order-title></div>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
    </div>
    <div class="order-popup__body">
        <div class="order-popup__grid">
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, т
                    <input class="order-popup__input" type="number" step="0.001" name="weight"
                           data-order-weight onkeyup="changeWeightPopup(this)">
                </label>
            </div>
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, м
                    <input class="order-popup__input" type="number" name="size" data-order-size>
                </label>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Цена, т</div>
                <div class="order-popup__input" data-order-price></div>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Сумма</div>
                <div class="order-popup__input" data-order-total></div>
            </div>
        </div>
        <div class="order-popup__action">
            <button class="button button--primary button--popup"
                    name="submit" aria-label="Добавить в корзину">
                <span>Добавить в корзину</span>
            </button>
        </div>
    </div>
</form>

<form class="order-popup popup" id="order_tonn_item" action="{{ route('ajax.add-to-cart') }}"
      style="display:none" onsubmit="addToCartProductPopup(this, event)">
    <div class="order-popup__head">
        <div class="order-popup__title" data-order-title></div>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
    </div>
    <div class="order-popup__body">
        <div class="order-popup__grid">
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, т
                    <input class="order-popup__input" type="number" step="0.001" name="weight"
                           data-order-weight onkeyup="changeWeightPopup(this)">
                </label>
            </div>
{{--            <div class="order-popup__col">--}}
{{--                <label class="order-popup__label">Количество, м--}}
{{--                    <input class="order-popup__input" type="number" name="size" data-order-size>--}}
{{--                </label>--}}
{{--            </div>--}}
            <div class="order-popup__col">
                <div class="order-popup__label">Цена, т</div>
                <div class="order-popup__input" data-order-price></div>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Сумма</div>
                <div class="order-popup__input" data-order-total></div>
            </div>
        </div>
        <div class="order-popup__action">
            <button class="button button--primary button--popup"
                    name="submit" aria-label="Добавить в корзину">
                <span>Добавить в корзину</span>
            </button>
        </div>
    </div>
</form>

<form class="order-popup popup" id="order_metr" action="{{ route('ajax.add-to-cart') }}"
      style="display:none" onsubmit="addToCartProductPopup(this, event)">
    <div class="order-popup__head">
        <div class="order-popup__title" data-order-title></div>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
    </div>
    <div class="order-popup__body">
        <div class="order-popup__grid">
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, м
                    <input class="order-popup__input" type="number"  name="weight"
                           data-order-weight onkeyup="changeWeightPopup(this)">
                </label>
            </div>
{{--            <div class="order-popup__col">--}}
{{--                <label class="order-popup__label">Количество, т--}}
{{--                    <input class="order-popup__input" type="number" step="0.001" name="size" data-order-size>--}}
{{--                </label>--}}
{{--            </div>--}}
            <div class="order-popup__col">
                <div class="order-popup__label">Цена, м</div>
                <div class="order-popup__input" data-order-price></div>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Сумма</div>
                <div class="order-popup__input" data-order-total></div>
            </div>
        </div>
        <div class="order-popup__action">
            <button class="button button--primary button--popup"
                    name="submit" aria-label="Добавить в корзину">
                <span>Добавить в корзину</span>
            </button>
        </div>
    </div>
</form>

<form class="order-popup popup" id="order_kilo" action="{{ route('ajax.add-to-cart') }}"
      style="display:none" onsubmit="addToCartProductPopup(this, event)">
    <div class="order-popup__head">
        <div class="order-popup__title" data-order-title></div>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
    </div>
    <div class="order-popup__body">
        <div class="order-popup__grid">
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, кг
                    <input class="order-popup__input" type="number" step="0.001" name="weight"
                           data-order-weight onkeyup="changeWeightPopup(this)">
                </label>
            </div>
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, шт
                    <input class="order-popup__input" type="number" name="size" data-order-size>
                </label>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Цена, кг</div>
                <div class="order-popup__input" data-order-price></div>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Сумма</div>
                <div class="order-popup__input" data-order-total></div>
            </div>
        </div>
        <div class="order-popup__action">
            <button class="button button--primary button--popup"
                    name="submit" aria-label="Добавить в корзину">
                <span>Добавить в корзину</span>
            </button>
        </div>
    </div>
</form>

<form class="order-popup popup" id="order_kilo_m2" action="{{ route('ajax.add-to-cart') }}"
      style="display:none" onsubmit="addToCartProductPopup(this, event)">
    <div class="order-popup__head">
        <div class="order-popup__title" data-order-title></div>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
    </div>
    <div class="order-popup__body">
        <div class="order-popup__grid">
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, кг
                    <input class="order-popup__input" type="number" step="0.001" name="weight"
                           data-order-weight onkeyup="changeWeightPopup(this)">
                </label>
            </div>
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, м2
                    <input class="order-popup__input" type="number" step="0.001" name="size" data-order-size>
                </label>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Цена, кг</div>
                <div class="order-popup__input" data-order-price></div>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Сумма</div>
                <div class="order-popup__input" data-order-total></div>
            </div>
        </div>
        <div class="order-popup__action">
            <button class="button button--primary button--popup"
                    name="submit" aria-label="Добавить в корзину">
                <span>Добавить в корзину</span>
            </button>
        </div>
    </div>
</form>

<form class="order-popup popup" id="order_item" action="{{ route('ajax.add-to-cart-pi') }}"
      style="display:none" onsubmit="addToCartProductItemPopup(this, event)">
    <div class="order-popup__head">
        <div class="order-popup__title" data-order-title></div>
        <div class="p-status in-stock">
            <span>В наличии</span>
            <svg width="15" height="16" viewBox="0 0 15 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.6562 4.71875L6.09375 11.281L2.8125 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

        </div>
    </div>
    <div class="order-popup__body">
        <div class="order-popup__grid">
            <div class="order-popup__col">
                <label class="order-popup__label">Количество, шт
                    <input class="order-popup__input" type="number" name="size" step="1"
                           data-order-size onkeyup="changeItemPopup(this)">
                </label>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Цена, т</div>
                <div class="order-popup__input" data-order-price></div>
            </div>
            <div class="order-popup__col">
                <div class="order-popup__label">Сумма</div>
                <div class="order-popup__input" data-order-total></div>
            </div>
        </div>
        <div class="order-popup__action">
            <button class="button button--primary button--popup"
                    name="submit" aria-label="Добавить в корзину">
                <span>Добавить в корзину</span>
            </button>
        </div>
    </div>
</form>

<form class="popup" id="callback" action="{{ route('ajax.callback') }}"
      onclick="sendCallback(this,event)" style="display:none">
    <div class="popup__container">
        <div class="popup__title">Заказать звонок</div>
        <div class="popup__subtitle">Введите данные для связи и наш менеджер свяжется с Вами в течении нескольких минут</div>
        <div class="popup__fields">
            <div class="field">
                <input class="field__input" type="text" name="name" required>
                <span class="field__highlight"></span>
                <span class="field__bar"></span>
                <label class="field__label">имя</label>
            </div>
            <div class="field">
                <input class="field__input" type="tel" name="phone" required>
                <span class="field__highlight"></span>
                <span class="field__bar"></span>
                <label class="field__label">телефон</label>
            </div>
        </div>
        <div class="popup__policy">
            <label class="checkbox">
                <input class="checkbox__input" type="checkbox" checked required>
                <span class="checkbox__box"></span>
                <span class="checkbox__policy">Даю согласие на обработку персональных данных.
							<a href="{{ route('policy') }}" target="_blank">Пользовательское соглашение</a>
						</span>
            </label>
        </div>
        <div class="popup__action">
            <button class="popup__submit btn-reset" name="submit" aria-label="Отправить">
                <span>Отправить</span>
            </button>
        </div>
    </div>
</form>

<form class="popup" id="question" action="{{ route('ajax.questions') }}" style="display:none"
      onsubmit="sendQuestion(this, event)">
    <div class="popup__container">
        <div class="popup__title">Задать вопрос</div>
        <div class="popup__subtitle">
            Менеджеры компании с радостью ответят на ваши вопросы и подскажут по ассортименту, и сделают лучшее предложение
        </div>
        <div class="popup__row">
            <div class="field">
                <input class="field__input" type="text" name="name" required>
                <span class="field__highlight"></span>
                <span class="field__bar"></span>
                <label class="field__label">имя</label>
            </div>
            <div class="field">
                <input class="field__input" type="tel" name="phone" required>
                <span class="field__highlight"></span>
                <span class="field__bar"></span>
                <label class="field__label">телефон</label>
            </div>
        </div>
        <div class="popup__data">
            <div class="field">
                <input class="field__input" type="text" name="question" required>
                <span class="field__highlight"></span>
                <span class="field__bar"></span>
                <label class="field__label">Ваше сообщение</label>
            </div>
        </div>
        <div class="popup__file">
            <div class="popup__file-upload">
                <label class="upload">
                    <span class="upload__name">Прикрепить файл</span>
                    <input type="file" name="file" accept=".jpg, .jpeg, .png, .pdf, .doc, .docs, .xls, .xlsx">
                </label>
            </div>
            <div class="popup__file-label">Размер файла не должен превышать 5 Мб. Вы можете прикрепить не более 10 файлов.</div>
        </div>
        <div class="popup__actions">
            <label class="checkbox">
                <input class="checkbox__input" type="checkbox" checked required>
                <span class="checkbox__box"></span>
                <span class="checkbox__policy">Даю согласие на обработку персональных данных.
							<a href="{{ route('policy') }}" target="_blank">Пользовательское соглашение</a>
						</span>
            </label>
            <button class="popup__submit btn-reset" name="submit" aria-label="Отправить">
                <span>Отправить</span>
            </button>
        </div>
    </div>
</form>

