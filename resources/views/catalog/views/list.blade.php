<div class="catalog-list">
    <div class="catalog-list__top">
        @include('catalog.views.togglers')
    </div>
    <div class="catalog-list__content">
        <div class="catalog-list__head">
            <div class="catalog-list__filter" data-filter-show>
                <svg class="svg-sprite-icon icon-option">
                    <use xlink:href="/static/images/sprite/symbol/sprite.svg#option"></use>
                </svg>
                <span>Показать фильтр</span>
            </div>
            <form action="{{ $category->url }}" id="filter_form">
                <div class="catalog-list__hidden" data-filter-content>
                    <div class="catalog-list__grid catalog-list__grid--filters">

                        <div class="catalog-list__column catalog-list__column--one">
                            <div class="catalog-list__label">Фото</div>
                        </div>
                        <div class="catalog-list__column catalog-list__column--two">
                            <div class="catalog-list__label">Наименование</div>
                        </div>
                        @if(count($filters))
                            @foreach($filters as $filter)
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <input type="hidden" name="filter_name{{ $loop->iteration }}" value="{{$filter->alias}}">
                                <div class="catalog-list__column catalog-list__column--one catalog-list__column--filter">
                                    <!-- https://slimselectjs.com/options-->
                                    <!-- js--sources/modules/select.js-->
                                    <select class="catalog-select" name="column{{ $loop->iteration }}[]" multiple
                                            onchange="updateFilter(this, event)">
                                        <option data-placeholder="true">{{ $filter->title }}</option>
                                        @if($sort)
                                            @foreach($sort[$filter->alias] as $value)
                                                <option value="{{ $value }}">{{ $value }}{{ $filter->measure }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endforeach
                            <div class="catalog-list__column catalog-list__column--two">
                                <div class="catalog-list__label">Цена</div>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(count($items))
            <div class="catalog-list__products">
                @foreach($items as $item)
                    @include('catalog.list_row')
                @endforeach
            </div>
        @endif
    </div>
    <div class="catalog-list__footer">
        @include('catalog.views.paginate')
        @include('catalog.views.per_page')
    </div>
</div>
