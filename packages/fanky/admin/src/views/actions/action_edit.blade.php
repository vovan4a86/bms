@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.actions') }}"><i class="fa fa-list"></i>Акции</a></li>
        @foreach($action->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.actions.products', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $action->id ? $action->name : 'Новая акция' }}</li>

    </ol>
@stop
@section('page_name')
    <h1>Акции
        <small>{{ $action->id ? $action->name : 'Новая акция' }}</small>
    </h1>
@stop

<form action="{{ route('admin.actions.actionSave') }}" onsubmit="return catalogSave(this, event)">
	<input type="hidden" name="id" value="{{ $action->id }}">

	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
			<li><a href="#tab_2" data-toggle="tab">Тексты</a></li>
            @if($action->id)
                <li class="pull-right">
                    <a href="{{ route('action.view', $action->id) }}" target="_blank">Посмотреть</a>
                </li>
            @endif
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">

                {!! Form::groupText('name', $action->name, 'Название') !!}
                {!! Form::groupDate('date', $action->date, 'Дата') !!}
                {!! Form::groupText('h1', $action->h1, 'H1') !!}

                {!! Form::groupSelect('parent_id', ['0' => '---корень---'] + $actions->pluck('name', 'id')->all(),
                    $action->parent_id, 'Родительский раздел') !!}
                {!! Form::groupText('alias', $action->alias, 'Alias') !!}
                {!! Form::groupText('title', $action->title, 'Title') !!}
                {!! Form::groupText('keywords', $action->keywords, 'keywords') !!}
                {!! Form::groupText('description', $action->description, 'description') !!}

                {!! Form::groupSelect('bgcolor', ['0' => 'Светлосиний', '1' => 'Темносиний'],
                           $action->bgcolor, 'Цвет фона') !!}
                {!! Form::groupText('badge', $action->badge, 'Бейдж') !!}
                <div class="form-group">
                    <label for="article-image">Иконка (*.svg)</label>
                    <input id="article-image" type="file" name="image" value="" onchange="return newsImageAttache(this, event)">
                    <div id="article-image-block">
                        @if ($action->image)
                            <img class="img-polaroid" src="{{ \Fanky\Admin\Models\Action::fileSrc($action->image) }}" height="100"
                                 data-image="{{ \Fanky\Admin\Models\Action::fileSrc($action->image) }}" onclick="return popupImage($(this).data('image'))">
                        @else
                            <p class="text-yellow">Изображение не загружено.</p>
                        @endif
                    </div>
                </div>
                {!! Form::groupRichtext('announce', $action->announce, 'Краткое описание', ['rows' => 3]) !!}
                {!! Form::hidden('published', 0) !!}
                {!! Form::groupCheckbox('published', 1, $action->published, 'Показывать раздел') !!}
			</div>

			<div class="tab-pane" id="tab_2">
                {!! Form::groupRichtext('text_before', $action->text_before, 'Текст перед товарами', ['rows' => 3]) !!}
                {!! Form::groupText('subtitle', $action->subtitle, 'Заголовок товаров') !!}
                {!! Form::groupRichtext('text_after', $action->text_after, 'Текст после товаров', ['rows' => 3]) !!}
			</div>
		</div>

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Сохранить</button>
		</div>
	</div>
</form>