@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_vacancies.js"></script>
@stop

@section('page_name')
    <h1>
        Вакансия
        <small>{{ $article->id ? 'Редактировать' : 'Новая' }}</small>
    </h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.vacancies') }}">Вакансия</a></li>
        <li class="active">{{ $article->id ? 'Редактировать' : 'Новая' }}</li>
    </ol>
@stop

@section('content')
    <form action="{{ route('admin.vacancies.save') }}" onsubmit="return vacancySave(this, event)">
        <input type="hidden" name="id" value="{{ $article->id }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
                <li><a href="#tab_2" data-toggle="tab">Текст</a></li>
{{--                @if($article->id)--}}
{{--                    <li class="pull-right">--}}
{{--                        <a href="{{ route('vacancies.item', [$article->alias]) }}" target="_blank">Посмотреть</a>--}}
{{--                    </li>--}}
{{--                @endif--}}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">

                    {!! Form::groupDate('date', $article->date, 'Дата') !!}
                    {!! Form::groupSelect('city_id', $cities->pluck('name', 'id')->all(), $article->city_id, 'Город') !!}
                    {!! Form::groupText('name', $article->name, 'Название') !!}
                    {!! Form::groupText('price', $article->price, 'Зарплата') !!}
                    {!! Form::groupText('alias', $article->alias, 'Alias') !!}
                    {!! Form::groupText('title', $article->title, 'Title') !!}
                    {!! Form::groupText('keywords', $article->keywords, 'keywords') !!}
                    {!! Form::groupText('description', $article->description, 'description') !!}

                    {!! Form::groupText('og_title', $article->og_title, 'OpenGraph Title') !!}
                    {!! Form::groupText('og_description', $article->og_description, 'OpenGraph description') !!}

                    {!! Form::groupCheckbox('published', 1, $article->published, 'Показывать вакансию') !!}
                </div>

                <div class="tab-pane" id="tab_2">
                    {!! Form::groupRichtext('text', $article->text, 'Текст', ['rows' => 3]) !!}
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@stop