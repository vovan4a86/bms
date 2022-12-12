<div class="catalog-list catalog-list--grid">
    <div class="catalog-list__content">
        @if(count($items))
            <div class="catalog-list__products catalog-list--grid">
                <div class="catalog-list__header">
                    @include('catalog.views.togglers')
                </div>
                <div class="catalog-list__cards">
                    @foreach($items as $item)
                        @include('catalog.grid_row')
                    @endforeach
                </div>
                <div class="catalog-list__bottom">
                    <div class="catalog-list__footer">
                        <nav class="pagination">
                            {{ $items->links('catalog.page_nav') }}
                        </nav>
                        @include('catalog.views.per_page')
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
