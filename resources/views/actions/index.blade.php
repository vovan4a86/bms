@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section stocks {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container">
                <h2 class="section__title section__title--inner">Акции</h2>
                @if($actions)
                <div class="stocks__list">
                    @foreach($actions as $item)
                    <a class="stock-item {{ $bgcolor[$item->bgcolor] ?? 'stock-item--accent' }}" href="{{ $item->url }}"
                       title="{{ $item->name }}">
                        <img class="stock-item__picture lazy" src="{{ \Fanky\Admin\Models\Action::fileSrc($item->image) }}" data-src="{{ \Fanky\Admin\Models\Action::fileSrc($item->image) }}" alt="" width="236" height="185">
                        <span class="stock-item__badge">{{ $item->badge }}</span>
                        <span class="stock-item__content">
								<span class="stock-item__date">{{ $item->dateFormat('d F Y') }}</span>
								<span class="stock-item__label">{!! $item->announce !!}</span>
							</span>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </section>
        <!-- class=errorPage ? '' : 'sending--margin'-->
        <section class="section sending">
            <h2 class="v-hidden">Оставить заявку</h2>
            <div class="container">
                <div class="sending__content lazy" data-bg="static/images/common/sending-decor.svg">
                    <div class="sending__title">Отправьте заявку в свободной форме и наши менеджеры сформируют предложение по вашему запросу</div>
                    <form class="sending__form form" action="#">
                        <div class="sending__row">
                            <label class="sending__label">Имя или Название организации
                                <span>*</span>
                                <input class="sending__input input" type="text" name="name" placeholder="Ваше имя" required>
                            </label>
                            <label class="sending__label">Телефон
                                <span>*</span>
                                <input class="sending__input input" type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                            </label>
                            <label class="sending__label">Email
                                <input class="sending__input input" type="text" name="email" placeholder="name@example.com">
                            </label>
                        </div>
                        <label class="sending__label">Сообщение
                            <span>*</span>
                            <textarea class="sending__input input textarea" rows="3" name="text" placeholder="Напишите сообщение" required></textarea>
                        </label>
                        <div class="sending__action">
                            <button class="btn">
                                <span>Отправить</span>
                            </button>
                            <label class="checkbox">
                                <input class="checkbox__input" type="checkbox" name="policy" checked required>
                                <span class="checkbox__box"></span>
                                <span class="checkbox__policy">Согласен на
										<a href="_ajax-policy.html" data-fancybox data-type="ajax">обработку персональных данных</a>
									</span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

@endsection