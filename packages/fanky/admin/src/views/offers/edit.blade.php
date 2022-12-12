@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_news.js"></script>
@stop

@section('page_name')
    <h1>
        Спецпредложение
        <small>{{ $offer->id ? 'Редактировать' : 'Новое' }}</small>
    </h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.offers') }}">Спецпредложение</a></li>
        <li class="active">{{ $offer->id ? 'Редактировать' : 'Новая' }}</li>
    </ol>
@stop

@section('content')
    <form action="{{ route('admin.offers.save') }}" onsubmit="return newsSave(this, event)">
        <input type="hidden" name="id" value="{{ $offer->id }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
                <li><a href="#tab_2" data-toggle="tab">Текст</a></li>
                @if($offer->id)
                    <li class="pull-right">
                        <a href="{{ route('offers.item', [$offer->alias]) }}" target="_blank">Посмотреть</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                    {!! Form::groupDate('date', $offer->date, 'Дата') !!}
                    {!! Form::groupText('name', $offer->name, 'Название') !!}
                    {!! Form::groupText('alias', $offer->alias, 'Alias') !!}
                    {!! Form::groupText('title', $offer->title, 'Title') !!}
                    {!! Form::groupText('keywords', $offer->keywords, 'keywords') !!}
                    {!! Form::groupText('description', $offer->description, 'description') !!}

                    {!! Form::groupText('og_title', $offer->og_title, 'OpenGraph Title') !!}
                    {!! Form::groupText('og_description', $offer->og_description, 'OpenGraph description') !!}
                    <div class="form-group">
                        <label for="article-image">Изображение</label>
                        <input id="article-image" type="file" name="image" value=""
                               onchange="return newsImageAttache(this, event)">
                        <div id="article-image-block">
                            @if ($offer->image)
                                <img class="img-polaroid" src="{{ $offer->thumb(1) }}" height="100"
                                     data-image="{{ $offer->image_src }}"
                                     onclick="return popupImage($(this).data('image'))">
                                <a class="images_del" href="{{ route('admin.offers.delete-image', [$offer->id]) }}" onclick="return newsImageDel(this, event)">
                                    <span class="glyphicon glyphicon-trash text-red"></span></a>
                            @else
                                <p class="text-yellow">Изображение не загружено.</p>
                            @endif
                        </div>
                    </div>

                    {!! Form::groupCheckbox('published', 1, $offer->published, 'Показывать спецпредложение') !!}
                    {!! Form::groupCheckbox('on_main_show', 1, $offer->on_main_show, 'Показывать на главной') !!}
                </div>

                <div class="tab-pane" id="tab_2">
                    {!! Form::groupTextarea('announce', $offer->announce, 'Краткое описание', ['rows' => 3]) !!}
                    {!! Form::groupRichtext('text', $offer->text, 'Текст', ['rows' => 3]) !!}
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@stop