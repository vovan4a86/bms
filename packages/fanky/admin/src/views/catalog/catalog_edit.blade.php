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


                @if(!$catalog->image && $catalog->section_image)
                    <img class="img-polaroid" src="{{ $catalog->section_image }}" height="100">
                @endif

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
                    {!! Form::hidden('on_menu', 1) !!}
                    {!! Form::groupCheckbox('on_menu', 1, $catalog->on_main, 'Показывать в главном меню') !!}
                    {!! Form::hidden('on_main_list', 1) !!}
                    {!! Form::groupCheckbox('on_main_list', 1, $catalog->on_main_list, 'Показывать в каталоге продукции') !!}
                    {!! Form::hidden('on_footer_menu', 1) !!}
                    {!! Form::groupCheckbox('on_footer_menu', 1, $catalog->on_footer_menu, 'Показывать в футере') !!}
                @endif

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <span class="box-title">Шаблон автооптимизации для товаров</span>
                    </div>
                    <div class="box-body">
                        {!! Form::groupText('product_title_template', $catalog->product_title_template, 'Шаблон title') !!}
                        <div class="small">Шаблон по умолчанию</div>
                        <div class="small">{{ \Fanky\Admin\Models\Product::$defaultTitleTemplate }}</div>
                        {!! Form::groupText('product_description_template', $catalog->product_description_template, 'Шаблон description') !!}
                        <div class="small">Шаблон по умолчанию</div>
                        <div class="small">{{ \Fanky\Admin\Models\Product::$defaultDescriptionTemplate }}</div>

                        {!! Form::groupRichtext('product_text_template', $catalog->product_text_template, 'Шаблон текста') !!}
                    </div>
                    <div class="box-footer">
                        Коды замены:
                        <ul>
                            <li>{name} - название товара</li>
                            <li>{lower_name} - название товара в нижнем регистре</li>
                            <li>{gost} - поле товара - Гост</li>
                            <li>{steel} - поле товара - Марка стали</li>
                            <li>{weight} - поле товара - Вес</li>
                        </ul>
                        Перед кодом {city} пробел не нужен. Шаблон применяется ко всем подразделам, если у них нет
                        своего шаблона
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab_2">
                {!! Form::groupRichtext('announce', $catalog->announce, 'Вводный текст', ['rows' => 3]) !!}
                {!! Form::groupRichtext('text', $catalog->text, 'Основной текст', ['rows' => 3]) !!}
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>
