@extends('template')
@section('content')
    @include('blocks.bread')
    <!-- headerIsWhite ? '' : 'page-head--dark'-->
    <div class="page-head {{ isset($headerIsBlack) ? 'page-head--dark' : null }}">
        <div class="page-head__container container">
            <div class="page-head__content">
                <div class="page-head__title">{{ $category->title }}</div>
                <div class="page-head__text">{!! $category->announce !!}</div>
            </div>
        </div>
    </div>
    <!-- class=productLayout && 'layout--product'-->
    <div class="layout">
        <div class="layout__container container">
            @include('catalog.blocks.aside')
            <div class="layout__content">
                <main>
                    <section class="s-subcatalog">
                        <div class="s-subcatalog__head">
                            <div class="s-subcatalog__search">
                                <form class="b-search" action="#">
                                    <input class="b-search__input" type="text" name="search-catalog"
                                           placeholder="Поиск по каталогу" autocomplete="off" required>
                                    <button class="b-search__button">
                                        <svg class="svg-sprite-icon icon-search">
                                            <use xlink:href="/static/images/sprite/symbol/sprite.svg#search"></use>
                                        </svg>
                                        <span>Найти</span>
                                    </button>
                                </form>
                            </div>
                            <div class="s-subcatalog__update">
                                <div class="c-update">
                                    <div class="c-update__title">Каталог обновлён:</div>
                                    <div class="c-update__date">16.10.2022</div>
                                </div>
                            </div>
                        </div>
                        <div class="t-catalog">
                            <div class="t-catalog__grid t-catalog__grid--head">
                                <div class="t-catalog__col t-catalog__col--wide t-catalog__col--select">
                                    <!-- https://slimselectjs.com/options-->
                                    <!-- js--sources/modules/selects.js-->
                                    <select class="select" name="product" data-single-select multiple>
                                        <option data-placeholder="true">Продукция</option>
                                        <option value="Трубы ВГП ДУ 6">Трубы ВГП ДУ 6</option>
                                        <option value="Трубы ВГП ДУ 8">Трубы ВГП ДУ 8</option>
                                        <option value="Трубы ВГП ДУ 10">Трубы ВГП ДУ 10</option>
                                        <option value="Трубы ВГП ДУ 15">Трубы ВГП ДУ 15</option>
                                        <option value="Трубы ВГП ДУ 20">Трубы ВГП ДУ 20</option>
                                        <option value="Трубы ВГП ДУ 25">Трубы ВГП ДУ 25</option>
                                        <option value="Трубы ВГП ДУ 32">Трубы ВГП ДУ 32</option>
                                        <option value="Трубы ВГП ДУ 40">Трубы ВГП ДУ 40</option>
                                        <option value="Трубы ВГП ДУ 50">Трубы ВГП ДУ 50</option>
                                    </select>
                                </div>
                                <div class="t-catalog__col t-catalog__col--select">
                                    <select class="select" name="product" data-multi-select multiple>
                                        <option data-placeholder="true">Размер</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                                <div class="t-catalog__col t-catalog__col--wide">
                                    <div class="t-catalog__col-label">Марка</div>
                                </div>
                                <div class="t-catalog__col">
                                    <div class="t-catalog__col-label">Длина</div>
                                </div>
                                <div class="t-catalog__col t-catalog__col--wide">
                                    <div class="t-catalog__col-label">Цена, руб</div>
                                </div>
                            </div>
                            @foreach($items as $item)
                                @include('catalog.product_item', compact($item))
                            @endforeach
                        </div>
                        <div class="s-subcatalog__content text-block">
                            {!! $category->text !!}
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
    @include('blocks.callback_form')
@endsection
