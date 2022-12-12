@section('page_name')
    <h1>Каталог
        <small>{{ $action->name }}</small>
    </h1>
@stop
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="{{ route('admin.actions') }}"><i class="fa fa-list"></i>Каталог</a></li>
        @foreach($action->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.actions.products', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $action->name}}</li>
    </ol>
@stop

<div class="box box-solid">
    <div class="box-body">
        <h4>Акция: <b>{{ $action->name}}</b></h4>

        <div class="form-group row">
            <div class="col-md-4">
                <select name="action-product-name" class="form-control">
                    @foreach($all_products as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.actions.add_action_product', $action->id) }}"
                   onclick="addActionProduct(this, event)" class="btn btn-primary">Добавить товар</a>
            </div>
        </div>

        <h3>Список товаров акции:</h3>
        <table class="table table-hover table-condensed" id="action_products_list">
            <thead>
            <tr>
                <th>Название</th>
                <th>Цена</th>
                <th>Каталог</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($action_products as $product)
                @include('admin::actions.product_row', ['product' => $product])
            @endforeach
            </tbody>
        </table>
    </div>
</div>