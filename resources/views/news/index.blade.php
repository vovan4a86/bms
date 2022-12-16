@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        @include('blocks.page_head')
        <section class="newses">
            <div class="newses__container container">
                <div class="newses__grid">
                    @foreach($items as $item)
                        @include('news.list_item')
                    @endforeach
                </div>
                    @include('paginations.with_pages' ,['paginator' => $items])
                </div>
            </div>
        </section>
    </main>
@endsection
