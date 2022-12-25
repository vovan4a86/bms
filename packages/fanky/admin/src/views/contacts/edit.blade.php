@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/autocomplete/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_contacts.js"></script>
@stop

@section('page_name')
    <h1>
        Контакты
        <small>{{ $contact->id ? 'Редактировать' : 'Новый' }}</small>
    </h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.contacts') }}">Контакты</a></li>
        <li class="active">{{ $contact->id ? 'Редактировать' : 'Новый' }}</li>
    </ol>
@stop

@section('content')
    <form action="{{ route('admin.contacts.save') }}" onsubmit="return contactSave(this, event)">
        <input type="hidden" name="id" value="{{ $contact->id }}">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
                <li><a href="#tab_2" data-toggle="tab">Соц. сети</a></li>
                @if($contact->id)
                    <li class="pull-right">
                        <a href="{{ route('contacts') }}" target="_blank">Посмотреть</a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1" style="max-width: 700px;">
                    {!! Form::groupText('title', $contact->title, 'Заголовок') !!}
                    {!! Form::groupSelect('city_id', $cities->pluck('name', 'id')->all(), $contact->city_id, 'Город') !!}
                    {!! Form::groupText('address', $contact->address, 'Адрес') !!}
                    <div style="display: flex; column-gap: 20px">
                        {!! Form::groupText('phone1', $contact->phone1, 'Телефон 1') !!}
                        {!! Form::groupText('phone1_comment', $contact->phone1_comment, 'Комментарий') !!}
                    </div>
                    <div style="display: flex; column-gap: 20px">
                        {!! Form::groupText('phone2', $contact->phone2, 'Телефон 2') !!}
                        {!! Form::groupText('phone2_comment', $contact->phone2_comment, 'Комментарий') !!}
                    </div>
                    {!! Form::groupText('email', $contact->email, 'Email') !!}
                    {!! Form::groupRichtext('work_days', $contact->work_days, 'График работы') !!}
                    <div style="display: flex; column-gap: 20px">
                        {!! Form::groupText('lat', $contact->lat, 'Ширина') !!}
                        {!! Form::groupText('long', $contact->long, 'Долгота') !!}
                    </div>
                </div>

                <div class="tab-pane" id="tab_2" style="max-width: 700px;">
                    {!! Form::groupText('whatsapp', $contact->whatsapp, 'Whatsapp') !!}
                    {!! Form::groupText('telegram', $contact->telegram, 'Telegram') !!}
                    {!! Form::groupText('skype', $contact->skype, 'Skype') !!}
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@stop