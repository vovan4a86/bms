@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="{{ route('admin.catalog') }}"><i class="fa fa-list"></i> Каталог</a></li>
        @foreach($catalog->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.catalog.products', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $catalog->id ? $catalog->name : 'Новый раздел' }}</li>

    </ol>
@stop
@section('page_name')
    <h1>Каталог
        <small>{{ $catalog->id ? $catalog->name : 'Новый раздел' }}</small>
    </h1>
@stop

<form action="{{ route('admin.catalog.catalogSave') }}" onsubmit="return catalogSave(this, event)">
    <input type="hidden" name="id" value="{{ $catalog->id }}">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Параметры</a></li>
            <li><a href="#tab_2" data-toggle="tab">Тексты</a></li>

            @if($catalog->parent_id == 0)
                {{--                <li><a href="#tab_params" data-toggle="tab">Характеристики</a></li>--}}
                <li><a href="#tab_3" data-toggle="tab">Фильтры сортировки</a></li>
            @endif
            @if($catalog->id)
                <li class="pull-right">
                    <a href="{{ $catalog->url }}" target="_blank">Посмотреть</a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">

                {!! Form::groupText('name', $catalog->name, 'Название') !!}
                {!! Form::groupText('h1', $catalog->h1, 'H1') !!}

                {!! Form::groupSelect('parent_id', ['0' => '---корень---'] + $catalogs->pluck('name', 'id')->all(),
                    $catalog->parent_id, 'Родительский раздел') !!}
                {!! Form::groupText('alias', $catalog->alias, 'Alias') !!}
                {!! Form::groupText('title', $catalog->title, 'Title') !!}
                {!! Form::groupText('keywords', $catalog->keywords, 'keywords') !!}
                {!! Form::groupText('description', $catalog->description, 'description') !!}


                <div class="form-group">
                    <label for="article-image">Изображение</label>
                    <input id="article-image" type="file" name="image" value=""
                           onchange="return newsImageAttache(this, event)">
                    <div id="article-image-block">
                        @if ($catalog->image)
                            <img class="img-polaroid" src="{{ $catalog->thumb(1) }}" height="100"
                                 data-image="{{ $catalog->image_src }}"
                                 onclick="return popupImage($(this).data('image'))">
                        @else
                            <p class="text-yellow">Изображение не загружено.</p>
                        @endif
                    </div>
                </div>
                {!! Form::hidden('published', 0) !!}
                {!! Form::hidden('on_main_list', 0) !!}
                {!! Form::groupCheckbox('published', 1, $catalog->published, 'Показывать раздел') !!}

                @if($catalog->parent_id == 0)
                    {!! Form::hidden('on_main_list', 0) !!}
                    {!! Form::groupCheckbox('on_main_list', 1, $catalog->on_main_list, 'Показывать в каталоге продукции') !!}
                @endif

                @if($catalog->parent_id !== 0 && count($catalog->public_children))
                    <hr>
                    <p>Отображаемые подразделы:</p>
                    @foreach($catalog->public_children as $child)
                        <div>
                            <input type="checkbox" name="show_cats[]"
                               {{ (in_array($child->id, $show_catalogs))? 'checked': '' }}
                               value="{{ $child->id }}"/>
                            {{ $child->name }}
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="tab-pane" id="tab_2">
                {!! Form::groupRichtext('announce', $catalog->announce, 'Вводный текст', ['rows' => 3]) !!}
                {!! Form::groupRichtext('text', $catalog->text, 'Основной текст', ['rows' => 3]) !!}
            </div>

            {{--            @if($catalog->parent_id == 0)--}}
            {{--                @include('admin::catalog.tabs.tab_params')--}}
            {{--            @endif--}}

            <div class="tab-pane" id="tab_3">
                @if(!$catalog->id)
                    <div>Добавление фильтров доступно только после сохранения каталога</div>
                @else
                    <div class="form-group" style="width:400px;">
                        @foreach($filters as $item)
                            <div>
                                <label for="{{ $item->alias }}">
                                    <input type="checkbox"
                                           name="filters[]"
                                           {{ (in_array($item->id, $filters_used)) ? 'checked': '' }}
                                           value="{{ $item->id }}"/>
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    {{--                    <table class="table table-striped table-v-middle">--}}
                    {{--                        <thead>--}}
                    {{--                        <tr>--}}
                    {{--                            <th width="100">Название</th>--}}
                    {{--                            <th width="100">Alias</th>--}}
                    {{--                            <th width="400">Title</th>--}}
                    {{--                        </tr>--}}
                    {{--                        </thead>--}}
                    {{--                        <tbody>--}}
                    {{--                        @foreach($filters as $item)--}}
                    {{--                            <tr>--}}
                    {{--                                <th>--}}
                    {{--                                    <div>--}}
                    {{--                                        <label for="{{ $item->alias }}">--}}
                    {{--                                            <input type="checkbox"--}}
                    {{--                                                   name="filters[]"--}}
                    {{--                                                   {{ (in_array($item->id, $filters_used)) ? 'checked': '' }}--}}
                    {{--                                                   value="{{ $item->id }}"/>--}}
                    {{--                                            {{ $item->name }}--}}
                    {{--                                        </label>--}}
                    {{--                                    </div>--}}
                    {{--                                </th>--}}
                    {{--                                <th>{{ $item->alias }}</th>--}}
                    {{--                                <th>--}}
                    {{--                                    <form class="input-group input-group-sm"--}}
                    {{--                                          action="{{ route('admin.catalog.update-filter-title', [$item->id]) }}"--}}
                    {{--                                          onsubmit="update_filter_title(this, event)">--}}
                    {{--                                        <input name="title" type="text" class="form-control" value="{{ $item->title }}">--}}
                    {{--                                        <span class="input-group-btn">--}}
                    {{--                                            <button class="btn btn-success btn-flat">--}}
                    {{--                                               <span class="glyphicon glyphicon-ok"></span>--}}
                    {{--                                            </button>--}}
                    {{--                                        </span>--}}
                    {{--                                    </form>--}}
                    {{--                                </th>--}}
                    {{--                            </tr>--}}
                    {{--                        @endforeach--}}
                    {{--                        </tbody>--}}
                    {{--                    </table>--}}
                @endif
            </div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
