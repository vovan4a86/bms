@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <!-- homepage ? '' : 'section--inner'-->
        <section class="catalog section {{ Request::url() === '/' ? '' : 'section--inner' }}">
            <div class="catalog__container container">
                <h2 class="section__title">{{ $h1 }}</h2>
                <div class="catalog__text text-content">
                    {!! $category->announce !!}
                </div>
                @if($category->parent_id !== 0)
                    <div class="catalog__links">
                        <nav class="subcategories">
                            <ul class="subcategories__list">
{{--                                <li class="subcategories__item">--}}
{{--                                    <a class="subcategories__link" href="{{ $root->url }}" title="Показать все">Показать все</a>--}}
{{--                                </li>--}}
                                @if($catalog_sub)
                                    @foreach($catalog_sub as $child)
                                        <li class="subcategories__item">
                                            <a class="subcategories__link"
                                               href="{{ $child->url }}" title="{{ $child->name }}">{{ $child->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </nav>
                        <div class="catalog__update">
                            <span>Цены обновлены {{ $updated !== null ? date_format($updated, 'd.m.Y') : null  }}</span>
                        </div>
                    </div>
                @else
                    @if(count($children))
                        <ul class="sublinks">
                            @foreach($children as $child)
                                <li class="sublinks__item">
                                    <a class="sublinks__link sublink" href="{{ $child->url }}"
                                       title="{{ $child->name }}">
                                        @if($child->image || $category->image)
                                            <img class="sublink__picture lazy" src="/"
                                                 data-src="{{ $child->image ? \Fanky\Admin\Models\Catalog::UPLOAD_URL . $child->image :
                                                                              \Fanky\Admin\Models\Catalog::UPLOAD_URL . $category->image }}"
                                                 alt="" width="64" height="34">
                                        @else
                                            <img class="sublink__picture lazy" src="/static/images/common/no_image.png"
                                                 data-src="/static/images/common/no_image.png"
                                                 alt="" width="64" height="34">
                                        @endif
                                        <span class="sublink__title">{{ $child->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="catalog__update">
                        <span>Цены обновлены {{ $updated !== null ? date_format($updated, 'd.m.Y') : null }}</span>
                    </div>
                @endif

            <!-- class=(catalogSubCatalogGrid ? 'catalog-list--grid' : '' )-->
                {!! $items !!}
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="section__text text-content">
                    {!! $category->text == null ? $root->text : $category->text  !!}
                </div>
            </div>
        </section>
    </main>
@endsection
