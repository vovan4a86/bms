@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        @include('blocks.page_head')
        <section class="s-partners">
            <div class="s-partners__container container">
                @if(count($partners))
                    <div class="s-partners__grid">
                        @foreach($partners as $item)
                            <div class="s-partners__item">
                                <img class="s-partners__pic lazy"
                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                     data-src="{{ \Fanky\Admin\Models\Partner::UPLOAD_URL . $item->image }}" width="{{ $item->image_width }}" height="{{ $item->image_height }}" alt="{{ $item->name }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection