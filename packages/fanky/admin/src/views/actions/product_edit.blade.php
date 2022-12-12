@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="{{ route('admin.catalog') }}"><i class="fa fa-list"></i> Каталог</a></li>
        @foreach($product->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.catalog.products', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $product->id ? $product->name : 'Новый товар' }}</li>
    </ol>
@stop
@section('page_name')
    <h1>Каталог
        <small>{{ $product->id ? $product->name : 'Новый товар' }}</small>
    </h1>
@stop

<form action="{{ route('admin.catalog.productSave') }}" onsubmit="return productSave(this, event)">
    {!! Form::hidden('id', $product->id) !!}

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li><a href="#tab_params" data-toggle="tab">Характеристики</a></li>

            <li class="pull-right">
                <a href="{{ route('admin.catalog.products', [$product->catalog_id]) }}" onclick="return catalogContent(this)">К списку товаров</a>
            </li>
            @if($product->id)
                <li class="pull-right">
                    <a href="{{ $product->url }}" target="_blank">Посмотреть</a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">

                {!! Form::groupText('name', $product->name, 'Название') !!}
                {!! Form::groupText('h1', $product->h1, 'H1') !!}
                {!! Form::groupText('subtit', $product->subtit, 'Подзаголовок') !!}
                {!! Form::groupSelect('catalog_id', $catalogs, $product->catalog_id, 'Каталог') !!}
                {!! Form::groupText('alias', $product->alias, 'Alias') !!}
                {!! Form::groupText('title', $product->title, 'Title') !!}
                {!! Form::groupText('keywords', $product->keywords, 'keywords') !!}
                {!! Form::groupText('description', $product->description, 'description') !!}
                {!! Form::groupText('articul', $product->articul, 'Артикул') !!}
                {!! Form::groupNumber('price', $product->price, 'Цена', ['step' => 1]) !!}

                {!! Form::groupCheckbox('published', 1, $product->published, 'Показывать товар') !!}
                {!! Form::hidden('in_stock', 0) !!}
                {!! Form::groupCheckbox('in_stock', 1, $product->in_stock, 'В наличии') !!}
{{--                {!! Form::groupCheckbox('on_main', 1, $product->on_main, 'Показывать на главной') !!}--}}

                {!! Form::hidden('is_action', 0) !!}
                {!! Form::groupCheckbox('is_action', 1, $product->is_action, 'Акция') !!}
                {!! Form::hidden('is_popular', 0) !!}
                {!! Form::groupCheckbox('is_popular', 1, $product->is_popular, 'Популярный товар') !!}

            </div>
            @include('admin::catalog.tabs.tab_icons')
            @include('admin::catalog.tabs.tab_params')
            <div class="tab-pane" id="tab_2">
                {!! Form::groupRichtext('product__points', $product->product__points, 'Преимущества на странице товара', ['rows' => 3]) !!}
                {!! Form::groupRichtext('announce_text', $product->announce_text, 'Краткое описание', ['rows' => 3]) !!}
                {!! Form::groupRichtext('text', $product->text, 'Текст', ['rows' => 3]) !!}
                {!! Form::groupRichtext('seo_text', $product->seo_text, 'SEO Текст', ['rows' => 3]) !!}
            </div>

            <div class="tab-pane" id="tab_4">
                <input id="product-image" type="hidden" name="image" value="{{ $product->image }}">
                @if ($product->id)
                    <div class="form-group">
                        <label class="btn btn-success">
                            <input id="offer_imag_upload" type="file" multiple data-url="{{ route('admin.catalog.productImageUpload', $product->id) }}" style="display:none;" onchange="productImageUpload(this, event)">
                            Загрузить изображения
                        </label>
                    </div>

                    <div class="images_list">
                        @foreach ($product->images()->orderBy('order')->get() as $image)
                            @include('admin::catalog.product_image', ['image' => $image, 'active' => $product->image])
                        @endforeach
                    </div>
                @else
                    <p class="text-yellow">Изображения можно будет загрузить после сохранения товара!</p>
                @endif
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(".images_list").sortable({
        update: function(event, ui) {
            var url = "{{ route('admin.catalog.productImageOrder') }}";
            var data = {};
            data.sorted = $('.images_list').sortable("toArray", {attribute: 'data-id'} );
            sendAjax(url, data);
            //console.log(data);
        },
    }).disableSelection();
</script>