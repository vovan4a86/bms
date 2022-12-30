<!-- headerIsWhite ? '' : 'page-head--dark'-->
<div class="page-head {{ isset($headerIsBlack) ? 'page-head--dark' : null }}">
    <div class="page-head__container container">
        <div class="page-head__content">
            <div class="page-head__title">{{ $title }}</div>
            @if(isset($announce))
                <div class="page-head__text">{!! $announce !!}</div>
            @endif
        </div>
    </div>
</div>
