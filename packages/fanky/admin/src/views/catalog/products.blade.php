@section('page_name')
    <h1>Каталог
        <small>{{ $catalog->name }}</small>
    </h1>
@stop
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="{{ route('admin.catalog') }}"><i class="fa fa-list"></i> Каталог</a></li>
        @foreach($catalog->getParents(false, true) as $parent)
            <li><a href="{{ route('admin.catalog.products', [$parent->id]) }}">{{ $parent->name }}</a></li>
        @endforeach
        <li class="active">{{ $catalog->name}}</li>
    </ol>
@stop

<div class="box box-solid">
    <div class="box-body">
        <a href="{{ route('admin.catalog.productEdit', ['catalog' => $catalog->id]) }}"
           class="btn btn-sm btn-primary"
           onclick="return catalogContent(this)">Добавить товар</a>

        @if (count($products))
            <table class="table table-striped table-v-middle">
                <thead>
                <tr>
                    <th width="100"></th>
                    <th>Название</th>
                    <th>Размер</th>
                    <th>Стенка</th>
                    <th>Сталь</th>
                    <th width="120">Цена за шт.</th>
                    <th width="120">Цена за м.</th>
                    <th width="120">Цена за кг.</th>
                    <th width="120">Цена за м2.</th>
                    <th width="120">Цена за т.</th>
                    <th width="130">Сортировка</th>
                    <th width="50"></th>
                </tr>
                </thead>
                <tbody id="catalog-products">
                @foreach ($products as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>
                            @if ($item->image()->first())
                                <img src="{{ $item->image()->first()->image }}" height="100" width="100">
                            @elseif(!$catalog->image && $catalog->section_image)
                                <img class="img-polaroid" src="{{ $catalog->section_image }}" height="100">
                            @endif
                        </td>
                        <td><a href="{{ route('admin.catalog.productEdit', [$item->id]) }}" onclick="return catalogContent(this)" style="{{ $item->published != 1 ? 'text-decoration:line-through;' : '' }}">{{ $item->name }}</a></td>
                        <td style="text-align: center">{{ $item->size ?? '-'}}</td>
                        <td style="text-align: center">{{ $item->wall ?? '-' }}</td>
                        <td style="text-align: center">{{ $item->steel ?? '-'}}</td>
                        <td style="text-align: center">{{ $item->price_per_item ?? '-' }}</td>
                        <td style="text-align: center">{{ $item->price_per_metr ?? '-' }}</td>
                        <td style="text-align: center">{{ $item->price_per_kilo ?? '-' }}</td>
                        <td style="text-align: center">{{ $item->price_per_m2 ?? '-' }}</td>
                        <td style="text-align: center">{{ $item->price ?? '-' }}</td>
                        <td>
                            <form class="input-group input-group-sm"
                                  action="{{ route('admin.catalog.update-order', [$item->id]) }}"
                                  onsubmit="update_order(this, event)">
                                <input type="number" name="order" class="form-control" step="1" value="{{ $item->order }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-success btn-flat" type="submit">
                                       <span class="glyphicon glyphicon-ok"></span>
                                    </button>
                                </span>
                            </form>
                        </td>
                        <td>
                            <a class="glyphicon glyphicon-trash" href="{{ route('admin.catalog.productDel', [$item->id]) }}" style="font-size:20px; color:red;" title="Удалить" onclick="return productDel(this)"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="text-yellow">В разделе нет товаров!</p>
        @endif
    </div>
</div>
