@if(isset($bread) && count($bread))
    <nav class="breadcrumbs">
        <div class="container">
            <ul class="breadcrumbs__list" itemscope="" itemtype="https://schema.org/BreadcrumbList">
                <!-- оставь заглушку о последней ссылки-->
                <li class="breadcrumbs__item" itemprop="itemListElement" itemscope=""
                    itemtype="https://schema.org/ListItem">
                    <a class="breadcrumbs__link breadcrumbs__link--home"
                       href="{{ route('main') }}" itemprop="item"> <span
                                class="" itemprop="name">Главная</span>
                        <meta itemprop="position" content="1">
                    </a>
                </li>
                @foreach($bread as $item)
                    <li class="breadcrumbs__item" itemprop="itemListElement" itemscope
                        itemtype="https://schema.org/ListItem">
                        <a class="breadcrumbs__link breadcrumbs__link"
                           href="{!! $loop->last ? 'javascript:void(0)': $item['url'] !!}" itemprop="item">
                            <span itemprop="name">{{ $item['name'] }}</span>
                            <meta itemprop="position" content="{{ $loop->index + 1 }}">
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
@endif