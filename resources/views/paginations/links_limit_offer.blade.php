@if($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator
    && $paginator->hasPages()
    && $paginator->lastPage() > 1)
        <? /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */  ?>

        <?php
        // config
        $link_limit = 7; // maximum number of links (a bit inaccurate, but will be ok for now)
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
        @if($paginator->hasMorePages())
            <button class="btn btn--loader" type="button"
                    data-url="{{ $paginator->nextPageUrl() }}" onclick="moreOffers(this, event)">
                <span>Показать ещё</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                    <path fill="currentColor"
                          d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z"
                          opacity=".5"/>
                    <path fill="currentColor" d="M20 12h2A10 10 0 0 0 12 2v2a8 8 0 0 1 8 8Z">
                        <animateTransform attributeName="transform" dur="1s" from="0 12 12"
                                          repeatCount="indefinite" to="360 12 12" type="rotate"/>
                    </path>
                </svg>
            </button>
        @endif
    @endif
@endif