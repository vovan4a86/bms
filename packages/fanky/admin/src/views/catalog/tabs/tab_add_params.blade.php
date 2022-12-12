<div class="tab-pane" id="tab_params">
    @if(!$product->id)
        <div>Добавление параметров доступно только после сохранения товара</div>
    @else
        <table class="table table-hover table-condensed" id="param_list">
            <thead>
            <tr>
                <th>Название</th>
                <th>Значение</th>
                <th>Группа</th>
                <th>В списке</th>
                <th>Спец.</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($product->params as $param)
                @include('admin::catalog.param_row', ['param' => $param])
            @endforeach
            </tbody>
        </table>

        <div class="form-group row">
            <div class="col-md-4">
                <input type="text" class="param-name form-control" placeholder="Название">
            </div>
            <div class="col-md-4">
                <input type="text" class="param-value form-control" placeholder="Значение">
            </div>
            <div class="col-md-4">
                <input type="text" class="param-group form-control" placeholder="Группа">
            </div>
            <div class="col-md-4">
                {!! Form::groupCheckbox('', 1, false, 'Показывать в списке товаров', ['class' => 'param-on_list']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::groupCheckbox('', 1, false, 'Показывать в шапке товара', ['class' => 'param-on_spec']) !!}
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.catalog.add_param', $product->id) }}" onclick="addParam(this, event)" class="btn btn-primary add-param">Добавить
                    параметр</a>
            </div>
        </div>
    @endif
</div>