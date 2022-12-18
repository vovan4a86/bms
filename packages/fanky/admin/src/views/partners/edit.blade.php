@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_partners.js"></script>
@stop

@section('page_name')
    <h1>
        Партнеры и поставщики
        <small>{{ $article->id ? 'Редактировать' : 'Новый' }}</small>
    </h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.partners') }}">Партнеры и поставщики</a></li>
        <li class="active">{{ $article->id ? 'Редактировать' : 'Новый' }}</li>
    </ol>
@stop

@section('content')
    <form action="{{ route('admin.partners.save') }}" onsubmit="return partnerSave(this, event)">
        <input type="hidden" name="id" value="{{ $article->id }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
                @if($article->id)
                    <li class="pull-right">
                        <a href="{{ route('partners') }}" target="_blank">Посмотреть</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    {!! Form::groupText('name', $article->name, 'Название') !!}
                    {!! Form::groupText('site', $article->site, 'URL') !!}
                    <div style="display: flex; column-gap: 20px">
                        {!! Form::groupText('image_width', $article->image_width, 'Ширина') !!}
                        {!! Form::groupText('image_height', $article->image_height, 'Высота') !!}
                    </div>
                    <div class="form-group">
                        <label for="article-image">Изображение</label>
                        <input id="article-image" type="file" name="image" value=""
                               onchange="return newsImageAttache(this, event)">
                        <div id="article-image-block">
                            @if ($article->image)
                                <img class="img-polaroid" src="{{ $article->thumb(1) }}" height="100"
                                     data-image="{{ $article->image_src }}"
                                     onclick="return popupImage($(this).data('image'))">
                                <a class="images_del" href="{{ route('admin.partners.delete-image', [$article->id]) }}" onclick="return partnerImageDel(this, event)">
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