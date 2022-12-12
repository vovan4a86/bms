<div class="tab-pane" id="tab_params">
    @if(!$catalog->id)
        <div>Добавление параметров доступно только после сохранения товара</div>
    @else
        <table class="table table-hover table-condensed" id="param_list">
            <thead>
            <tr>
                <th>Название</th>
                <th>Alias</th>
                <th>Измерение</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($catalog->add_params as $param)
                @include('admin::catalog.param_row', ['param' => $param])
            @endforeach
            </tbody>
        </table>

        <div class="form-group row">
            <div class="col-md-3">
                <input type="text" class="param-name form-control" placeholder="Название">
            </div>
            <div class="col-md-3">
                <input type="text" class="param-alias form-control" placeholder="Alias">
            </div>
            <div class="col-md-3">
                <input type="text" class="param-measure form-control" placeholder="Измерение">
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.catalog.add_param', $catalog->id) }}"
                   onclick="addParam(this, event)"
                   class="btn btn-primary add-param">Добавить
                </a>
            </div>
        </div>
    @endif
</div>
