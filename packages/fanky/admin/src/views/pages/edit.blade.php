@section('page_name')
    <h1>Структура сайта
        <small>{{ $page->id ? 'Редактировать страницу' : 'Новая страница' }}</small>
    </h1>
@stop
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="{{ route('admin.pages') }}"><i class="fa fa-sitemap"></i> Структура сайта</a></li>
        @foreach($page->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.pages.edit', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $page->id ? $page->name : 'Новая страница' }}</li>
    </ol>
@stop

<form action="{{ route('admin.pages.save') }}" onsubmit="return pageSave(this, event)">
    <input id="page-id" type="hidden" name="id" value="{{ $page->id }}">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
            @if(in_array($page->id, $gostsIds) && $page->id !== 15)
                <li><a href="#files" data-toggle="tab">Файлы ({{ $page->gostFiles->count() }})</a></li>
            @endif
            @if (count($setting_groups))
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">Настройки <span
                                class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach ($setting_groups as $item)
                            <li><a href="#tab_setting_{{ $item->id }}" data-toggle="tab">{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if (count($galleries))
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">Галереи <span
                                class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach ($galleries as $item)
                            <li><a href="#tab_gallery_{{ $item->id }}" data-toggle="tab">{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
            @if($page->id)
                <li class="pull-right">
                    <a href="{{ $page->url }}" target="_blank">Посмотреть</a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                {!! Form::groupText('name', $page->name, 'Название') !!}
                {!! Form::groupText('h1', $page->h1, 'H1') !!}
                {!! Form::groupText('og_title', $page->og_title, 'OpenGraph Title') !!}
                {!! Form::groupText('og_description', $page->og_description, 'OpenGraph Description') !!}

                @if(!in_array($page->alias, \Fanky\Admin\Models\Page::$excludePageImage))
                    <div class="form-group">
                        <label for="article-image">Изображение</label>
                        <input id="article-image" type="file" name="image" value=""
                               onchange="return newsImageAttache(this, event)">
                        <div id="article-image-block">
                            @if ($page->image)
                                <img class="img-polaroid" src="{{ $page->thumb(1) }}" height="100"
                                     data-image="{{ $page->thumb(1) }}"
                                     onclick="return popupImage($(this).data('image'))">
                            @else
                                <p class="text-yellow">Изображение не загружено.</p>
                            @endif
                        </div>
                    </div>
                @endif
                @if($page->id == 1)
                    {!! Form::hidden('parent_id', 0) !!}
                @else
                    {!! Form::groupSelect('parent_id', $pages, $page->parent_id, 'Родительская страница') !!}
                @endif

                {!! Form::groupText('alias', $page->alias, 'Alias', ['disabled' => $page->system == 1]) !!}
                {!! Form::groupText('title', $page->title, 'Title') !!}
                {!! Form::groupText('keywords', $page->keywords, 'keywords') !!}
                {!! Form::groupText('description', $page->description, 'description') !!}

                @if(!in_array($page->alias, \Fanky\Admin\Models\Page::$excludePageText))
                    {!! Form::groupRichtext('text', $page->text, 'Текст') !!}
                @endif

                {!! Form::hidden('published', 0) !!}
                {!! Form::hidden('on_top_menu', 0) !!}
                {!! Form::hidden('on_footer_menu', 0) !!}
                {!! Form::groupCheckbox('published', 1, $page->published, 'Показывать страницу') !!}
                {!! Form::groupCheckbox('on_top_menu', 1, $page->on_top_menu, 'Показывать в верхнем меню') !!}
                {!! Form::hidden('on_menu', 0) !!}
                {!! Form::groupCheckbox('on_menu', 1, $page->on_menu, 'Показывать в главном меню') !!}
                {!! Form::groupCheckbox('on_footer_menu', 1, $page->on_footer_menu, 'Показывать в футере') !!}
            </div>

{{--            вкладка Файлы для ГОСТов--}}
            @if(in_array($page->id, $gostsIds))
                @include('admin::pages.tabs.gost_files')
            @endif

            @foreach ($setting_groups as $item)
                <div class="tab-pane" id="tab_setting_{{ $item->id }}">
                    <h4>{{ $item->name }}</h4>
                    @if ($item->description)
                        <blockquote><small>{{ $item->description }}</small></blockquote>
                    @endif

                    <input type="hidden" name="setting_group[]" value="{{ $item->id }}">

                    <a class="margin popup-ajax" href="{{ route('admin.settings.edit').'?group='.$item->id }}">Добавить
                        настройку</a>
                    <div id="settings-group-{{ $item->id }}">
                        @include('admin::settings.items', ['settings' => $item->settings()->orderBy('order')->get()])
                    </div>
                </div>
            @endforeach

            <script type="text/javascript"> $('.setting-items-list').sortable({handle: '.handle'}).disableSelection(); </script>
            <script type="text/javascript"> $('.setting-gal-list').sortable({handle: '.images_move'}).disableSelection(); </script>

            @foreach ($galleries as $item)
                <div class="tab-pane" id="tab_gallery_{{ $item->id }}">
                    <h4>{{ $item->name }}</h4>
                    @include('admin::gallery.items', ['gallery' => $item, 'items' => $item->items()->orderBy('order')->get()])
                </div>
            @endforeach

        </div>


        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
