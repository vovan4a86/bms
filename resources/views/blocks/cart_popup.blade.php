<div class="popup-order popup popup--order" id="edit-order" style="display: none">
    <div class="popup-order__head">
        <div class="popup-order__title" data-popup-title></div>
        <input type="hidden" id="order-id" data-popup-order>
        <div class="popup-order__status" data-popup-instock>
        </div>
        <div class="popup-order__price">Цена
            <span data-popup-price></span>
        </div>
    </div>
    <div class="popup-order__fields">
        <div class="popup-order__field">
            <label class="popup-order__label">Длина, м
                <input class="popup-order__input" type="number" name="length" value="5" disabled required data-popup-length>
            </label>
        </div>
        <div class="popup-order__field">
            <label class="popup-order__label">Кол-во, шт
                <input class="popup-order__input" type="number" name="count" value="1" required data-popup-count>
            </label>
        </div>
        <div class="popup-order__field">
            <label class="popup-order__label">Кол-во, <span style="display: contents;" data-popup-measure></span>
                <input class="popup-order__input" type="number" name="weight" value="1" step="0.1" required data-popup-weight>
            </label>
        </div>
        <div class="popup-order__value">
            <label class="popup-order__label">На сумму, ₽
                <input class="popup-order__input" type="text" name="total" data-popup-total disabled>
            </label>
        </div>
    </div>
    <div class="popup-order__stats">
        <div class="popup-order__weights">Общий вес продукции: <span style="display: contents;" data-popup-weighttot></span></div>
        <div class="popup-order__total-value">
            <span>Итого</span>
            <div class="popup-order__summary" data-end="₽" data-popup-summary></div>
        </div>
    </div>
    <div class="popup-order__action">
        <button class="btn btn--content" type="button">
            <span>Обновить</span>
        </button>
    </div>
</div>
