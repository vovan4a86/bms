@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_delivery.js"></script>
@stop

@section('page_name')
    <h1>
        Способ доставки
        <small>{{ $item->id ? 'Редактировать' : 'Новый' }}</small>
    </h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.delivery') }}">Способы доставки</a></li>
        <li class="active">{{ $item->id ? 'Редактировать' : 'Новый' }}</li>
    </ol>
@stop

@section('content')
    <form action="{{ route('admin.delivery.save') }}" onsubmit="return deliveryItemSave(this, event)">
        <input type="hidden" name="id" value="{{ $item->id }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    {!! Form::groupText('name', $item->name, 'Название') !!}
                    {!! Form::groupText('description', $item->description, 'Описание') !!}
                    {!! Form::groupText('text', $item->text, 'Текст') !!}
                    {!! Form::groupText('price', $item->price, 'Стоимость доставки') !!}
                    {!! Form::groupText('free', $item->free, 'Бесплатно при заказе, от') !!}
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@stop
