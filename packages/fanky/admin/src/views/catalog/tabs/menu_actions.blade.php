<style>
    input, select {
        margin: 8px 0;
    }

    .action-link {
        padding: calc(15px + 0.78125vw) calc(15px + 0.5729166667vw);
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 100%;
        max-width: 410px;
        border-radius: 4px;
        overflow: hidden;
        text-decoration: none;
    }

    .action-link--green {
        background-color: #44da6e;
        color: #f3f5f9;
    }

    .action-link--blue {
        background-color: #4985df;
        color: #f3f5f9;
    }

    .action-link__picture {
        position: absolute;
        right: 0;
        bottom: 0;
    }

    .action-link__title {
        margin-bottom: 6px;
        font-weight: 600;
        font-size: calc(18px + 0.2083333333vw);
        line-height: 1.45;
    }

    .action-link__subtitle {
        font-weight: 500;
        font-size: 15px;
        line-height: 1.53;
        letter-spacing: 0.04em;
    }

    .action-link__price {
        margin-top: calc(20px + 0.625vw);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: end;
        -ms-flex-align: end;
        align-items: flex-end;
        font-weight: 500;
        font-size: 15px;
        line-height: 2.13;
        letter-spacing: 0.04em;
        text-transform: lowercase;
    }

    .action-link__current {
        font-weight: 700;
        font-size: 18px;
        line-height: 1.78;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--white);
    }
</style>

@if(!$catalog->id)
    <div>Добавление акций доступно только после сохранения категории</div>
@else
    <div class="overlay-nav__actions">
        @foreach($catalog->menu_actions as $action)
            @include('admin::catalog.tabs.menu_action_item')
        @endforeach
    </div>
    <hr>
    <div class="form-group row add-action">
        <div class="col-md-6">
            <select name="menu-action-productId" class="form-control">
                @foreach($catalogProducts as $name => $id)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            <input type="text" name="menu-action-title" class="form-control" placeholder="Заголовок">
            <input type="text" name="menu-action-text" class="form-control" placeholder="Текст">

            <div style="display: flex;">
                <input type="text" name="menu-action-price" class="form-control" placeholder="Цена">
                <input type="text" name="menu-action-measure" class="form-control" placeholder="Измерение">
            </div>
        </div>

        <div class="col-md-6">
            <h6 style="font-weight: bold">Цвет фона</h6>
            <select name="menu-action-style" class="form-control">
                @foreach(['Зеленый' => 'action-link--green', 'Голубой' => 'action-link--blue'] as $name => $value)
                    <option value="{{ $value }}">{{ $name }}</option>
                @endforeach
            </select>
            <h6 style="font-weight: bold">Изображение (.png 300x350)</h6>
            <input id="menu-action-image" type="file" name="menu-image" value="">
        </div>
        <div class="col-md-12">
            <a href="{{ route('admin.catalog.addMenuAction', $catalog->id) }}"
               onclick="addMenuAction(this, event)" class="btn btn-primary">Добавить
            </a>
        </div>
    </div>
@endif
