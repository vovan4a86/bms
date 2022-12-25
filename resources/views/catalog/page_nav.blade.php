@if($paginator->hasMorePages())
    <ul class="pagination__list">
    @foreach ($elements as $element)
        <!-- Массив ссылок -->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination__item">
                            <span class="pagination__link pagination__link--current">{{ $page }}</span>
                        </li>
                    @else
                        <li class="pagination__item">
                            <a class="pagination__link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

    <!-- Ссылка на следующую страницу -->
        @if ($paginator->hasMorePages())
            <li class="pagination__item">
                <a class="pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <svg class="svg-sprite-icon icon-triangle">
                        <use xlink:href="/static/images/sprite/symbol/sprite.svg#triangle"></use>
                    </svg>
                </a>
            </li>
        @else
            <li class="pagination__item disabled">
            <span class="pagination__link">
                <svg class="svg-sprite-icon icon-triangle">
                    <use xlink:href="/static/images/sprite/symbol/sprite.svg#triangle"></use>
                </svg>
            </span>
            </li>
        @endif
    </ul>
@endif
