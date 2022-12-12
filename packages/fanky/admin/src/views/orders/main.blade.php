@extends('admin::template')

@section('scripts')
    <script type="text/javascript" src="/adminlte/interface_orders.js"></script>
@stop

@section('page_name')
    <h1>Заказы</h1>
@stop

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Заказы</li>
    </ol>
@stop

@section('content')
    <div class="box box-solid">
        <div class="box-body">
            @if (count($orders))
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="150">Дата</th>
                        <th>Заказчик</th>
                        <th>Тип</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th>Сумма</th>
                        <th>Способ доставки</th>
{{--                        <th>ID платежа</th>--}}
{{--                        <th>Статус платежа</th>--}}
                        <th width="50"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $item)
                        <tr>
                            <td>{{ $item->dateFormat() }}
                            @if($item->new)<span class="label label-danger">NEW</span>@endif
                            </td>
                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ $item->name }}</a></td>
                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ array_get(\Fanky\Admin\Models\Order::$user_type, $item->user) }}</a></td>
                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ $item->phone }}</a></td>
                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ $item->email }}</a></td>
                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ $item->summ }}</a></td>
                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ array_get(\Fanky\Admin\Models\Order::$delivery_method, $item->delivery_method) }}</a></td>

                            {{--                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ $item->payment_order()->payment_id }}</a></td>--}}
{{--                            <td><a href="{{ route('admin.orders.view', [$item->id]) }}">{{ array_get(\Fanky\Admin\Models\PaymentOrder::$statuses, $item->payment_order()->status_id) }}</a></td>--}}
                            <td>
                                <a class="glyphicon glyphicon-trash" href="{{ route('admin.orders.del', [$item->id]) }}" style="font-size:20px; color:red;" title="Удалить" onclick="return orderDel(this)"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $orders->render() !!}
            @else
                <p>Нет заказов!</p>
            @endif
        </div>
    </div>
@stop
