<!DOCTYPE html>
<html lang="ru">
@include('blocks.head')
<body class="no-scroll">
    {!! Settings::get('counters') !!}
    @include('blocks.preloader')
    @include('blocks.header')

    @yield('content')

    @include('blocks.footer')
{{--    @include('blocks.cookie')--}}
    @include('blocks.mobile_nav')
    @include('blocks.popups')

    <div class="v-hidden" id="company" itemprop="branchOf" itemscope itemtype="https://schema.org/Corporation" aria-hidden="true" tabindex="-1">
        <article itemscope itemtype="https://schema.org/LocalBusiness" itemref="company">
            {{ Settings::get('schema.org') }}
        </article>
    </div>
    <div>
{{--        <script src="/static/js/manifest.js"></script>--}}
{{--        <script src="/static/js/vendor.js"></script>--}}
        <script src="{{ mix('static/js/all.js') }}"></script>
{{--        <script src="/static/js/main.js"></script>--}}
        <script src="/static/js/custom.js"></script>
    </div>
</body>
</html>
