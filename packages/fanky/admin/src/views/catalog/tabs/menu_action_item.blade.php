<div style="display: flex;" id="menu-action-{{ $action->id }}">
    @include('admin::catalog.tabs.menu_action_span')

    <div style="display: flex; flex-direction: column; margin: 0 8px;">
    <a class="glyphicon glyphicon-trash" href="{{ route('admin.catalog.deleteMenuAction', [$action->id]) }}"
       style="font-size:20px; color:red;" title="Удалить" onclick="return delMenuAction(this, event, {{$action->id}})"></a>

    <a class="glyphicon glyphicon-pencil" style="font-size:20px; color:red; margin-top: 10px; cursor: pointer;"
       title="Изменить" onclick="return showHiddenMenuAction({{$action->id}})"></a>

    <a class="glyphicon glyphicon-ok" href="{{ route('admin.catalog.updateMenuAction', [$action->id]) }}"
       style="font-size:20px; color:red; margin-top: 10px;" title="Применить" onclick="return updateMenuAction(this, event, {{$action->id}})"></a>
    </div>

    <div class="menu-action-hidden" style="display: none;">
        <input type="text" name="menu-action-title" class="form-control" value="{{$action->title}}" placeholder="Заголовок">
        <input type="text" name="menu-action-text" class="form-control" value="{{$action->text}}" placeholder="Текст">
        <input type="text" name="menu-action-price" class="form-control" value="{{$action->price}}" placeholder="Цена">
        <input type="text" name="menu-action-measure" class="form-control" value="{{$action->measure}}" placeholder="Измерение">
    </div>
</div>
<br>
