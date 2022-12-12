@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="sale section {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="sale__container container">
                <h2 class="section__title">{{ $action->name }}</h2>
                <div class="sale__head text-content">
                    {!! $action->text_before !!}
                </div>
                <div class="sale__content">
                    <h3 class="sale__subtitle">{{ $action->subtitle }}</h3>
                    @if(count($action_products))
                        @foreach($action_products as $cat_name => $products)
                            <h4 class="sale__category">{{ $cat_name }}</h4>
                            <div class="sale__grid">
                                @foreach($products as $product)
                                    <div class="sale__card">
                                    <!-- card-->
                                    <div class="card swiper-slide">
                                        @if($product->is_action)
                                            <div class="card__badge">%</div>
                                        @endif
                                        <a class="card__preview" href="{{ $product->url }}" title="{{ $product->name }}">
                                            <img class="card__picture lazy" src="{{ $product->url }}"
                                                 data-src="{{ $product->showCategoryImage($product->catalog_id) }}" width="200" height="130"
                                                 alt="{{ $product->name }}" />
                                        </a>
                                        <div class="card__status">
                                            @if($product->in_stock)
                                                <div class="product-status product-status--instock">
                                                    В наличии
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.4375 2.81274L4.0625 7.18755L1.875 5.00024" stroke="#52AA52" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="product-status product-status--out-stock">Под заказ</div>
                                            @endif
                                        </div>
                                        <h3 class="card__title">
                                            <a href="{{ $product->url }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="card__price price-card">
                                            <span class="price-card__label">Цена:</span>
                                            <span class="price-card__value">{{ $product->price ? $product->getFullPrice() . ' ₽' : 'по запросу' }}</span>
                                            <span class="price-card__counts">{{ $product->price ? '/ ' . $product->measure : '' }}</span>
                                        </div>
                                            @include('cart.card_actions')
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="section__text text-content">
                    {!! $action->text_after !!}
                </div>
            </div>
        </section>
    </main>
@endsection
