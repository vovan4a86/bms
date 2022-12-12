<form action="{{ $category->url }}" id="setView">
    <div class="togglers">
        <span class="togglers__toggle {{ session('catalog_view', 'list') == 'grid' ? 'togglers__toggle--active': '' }}"
              onclick="setView(this, 'grid')"
              title="Вид сетка">
            <svg class="svg-sprite-icon icon-grid-view">
                <use xlink:href="/static/images/sprite/symbol/sprite.svg#grid-view"></use>
            </svg>
        </span>
        <span class="togglers__toggle {{ session('catalog_view', 'list') == 'list' ? 'togglers__toggle--active': '' }} "
              onclick="setView(this, 'list')"
           title="Вид список">
            <svg class="svg-sprite-icon icon-list-view">
                <use xlink:href="/static/images/sprite/symbol/sprite.svg#list-view"></use>
            </svg>
        </span>
    </div>
</form>
