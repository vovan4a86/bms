@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_news.js"></script>
@stop

@section('page_name')
    <h1>
        Иконки преимуществ
        <small>{{ $item->id ? 'Редактировать' : 'Новая' }}</small>
    </h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="{{ route('admin.product-icons') }}">Иконки преимуществ</a></li>
        <li class="active">{{ $item->id ? 'Редактировать' : 'Новая' }}</li>
    </ol>
@stop

@section('content')
    <form action="{{ route('admin.product-icons.save') }}" onsubmit="return newsSave(this, event)">
        <input type="hidden" name="id" value="{{ $item->id }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <p>Текст помещенный в тег SPAN будет черного цвета и на новой строке</p>
                    {!! Form::groupText('name', $item->name, 'Название') !!}
                    {!! Form::groupText('order', $item->order ?? 0, 'Порядок') !!}
                    <div class="form-group">
                        <label for="article-image">Изображение (42x42)</label>
                        <input id="article-image" type="file" name="image" value=""
                               onchange="return newsImageAttache(this, event)">
                        <div id="article-image-block">
                            @if ($item->image)
                                <img class="img-polaroid" src="{{ \Fanky\Admin\Models\ProductIcon::UPLOAD_URL . $item->image }}" height="100"
                                     data-image="{{ \Fanky\Admin\Models\ProductIcon::UPLOAD_URL . $item->image }}"
                                     onclick="return popupImage($(this).data('image'))">
                                <a class="images_del" href="{{ route('admin.product-icons.delete-image', [$item->id]) }}" onclick="return newsImageDel(this, event)">
                                    <span class="glyphicon glyphicon-trash text-red"></span></a>
                            @else
                                <p class="text-yellow">Изображение не загружено.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@stop
