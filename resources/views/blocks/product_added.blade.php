<div class="popup" id="added">
    <div class="popup__title">Товар добавлен</div>
    <div class="popup__fields">
        {{ $name ?? '' }} <span>{{ $count ?? '' }}шт</span> {{ $price ?? '' }} руб</div>
    <div class="popup__action">
        <a class="btn added__close" href="#">Продолжить</a>
        <a class="btn added__tocart" href="/cart">Оформить</a>
    </div>
</div>