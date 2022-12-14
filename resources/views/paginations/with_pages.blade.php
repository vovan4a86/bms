@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator
    && $paginator->hasPages()
    && $paginator->lastPage() > 1)
        <? /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */ ?>

        <?php
        // config
        $link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
        $half_total_links = floor($link_limit / 2);
        $from = $paginator->currentPage() - $half_total_links;
        $to = $paginator->currentPage() + $half_total_links;
        if ($paginator->currentPage() < $half_total_links) {
            $to += $half_total_links - $paginator->currentPage();
        }
        if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
            $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
        }
        ?>

    @if ($paginator->lastPage() > 1)
        <div class="pagination">
            @if ($paginator->currentPage() > 1)
                <a class="pagination__link pagination__link--btn" href="{{ $paginator->previousPageUrl() }}" title="Назад">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 3.75L6.25 10L12.5 16.25" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif
            @if($from > 1)
                <a class="pagination__link" href="{{ $paginator->url(1) }}" title="Страница 1">
                    <span>1</span>
                </a>
            @endif

            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                @if ($from < $i && $i < $to)
                    @if ($i == $paginator->currentPage())
                        <a class="pagination__link pagination__link--current" href="{{ $paginator->url($i) }}"
                           title="Страница {{ $i }}">
                            <span>{{ $i }}</span>
                        </a>
                    @else
                        <a class="pagination__link" href="{{ $paginator->url($i) }}" title="Страница {{ $i }}">
                            <span>{{ $i }}</span>
                        </a>
                    @endif
                @endif
            @endfor
            @if($to < $paginator->lastPage())
                <a class="pagination__link" href="{{ $paginator->url($paginator->lastPage()) }}"
                   title="Последняя страница">
                    ...
                </a>
            @endif
            @if ($paginator->currentPage() < $paginator->lastPage())
                <a class="pagination__link pagination__link--btn" href="{{ $paginator->nextPageUrl() }}"
                   title="Далее">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.5 3.75L13.75 10L7.5 16.25" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </a>
            @endif
        </div>
    @endif
@endif



