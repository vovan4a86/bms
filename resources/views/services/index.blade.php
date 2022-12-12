@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="section services {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="container services__container">
                <h2 class="section__title section__title--inner">Услуги</h2>
                @if(count($categories))
                <div class="services__list">
                    @foreach($categories as $cat)
                        <div class="services__item">
                        <h3 class="v-hidden">{{ $cat->name }}</h3>
                        <a class="card-link" href="{{ $cat->url }}" title="{{ $cat->name }}">
                            <span class="card-link__bg lazy" data-bg="{{ $cat->thumb(2) }}"></span>
                            <span class="card-link__footer">
									<span class="card-link__label">{{ $cat->name }}</span>
									<span class="card-link__icon">
										<svg class="svg-sprite-icon icon-arrow">
											<use xlink:href="/static/images/sprite/symbol/sprite.svg#arrow"></use>
										</svg>
									</span>
								</span>
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                    <div class="services__list">
                        <h4>Пока пусто</h4>
                    </div>
                @endif
            </div>
        </section>
    </main>

@endsection