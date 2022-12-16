<div class="layout__aside">
    <aside class="aside">
        <div class="aside-nav">
            <ul class="aside-nav__list list-reset">
                @foreach($categories as $category)
                    <li class="aside-nav__item">
                        <a class="aside-nav__link" href="{{ $category->url }}"
                           title="{{ $category->name }}">
                            <span class="aside-nav__link-name">{{ $category->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                 viewBox="0 0 19 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="1.5" d="M7.25 2.375 12.875 8 7.25 13.625"/>
                            </svg>
                        </a>
                        @if($children = $category->public_children)
                            <ul class="aside-nav__sublist list-reset">
                                @foreach($children as $child)
                                    <li class="aside-nav__subitem">
                                        <a class="aside-nav__sublink" href="{{ $child->url }}">{{ $child->name }}</a>
                                        @if($grandsons = $child->public_children)
                                            <ul class="aside-nav__child-list list-reset">
                                                @foreach($grandsons as $grandson)
                                                    <li class="aside-nav__child-subitem">
                                                        <a class="aside-nav__child-sublink"
                                                           href="{{ $grandson->url }}">{{ $grandson->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>
