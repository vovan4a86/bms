@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_news.js"></script>
@stop

@section('page_name')
	<h1>Иконки преимуществ
		<small><a href="{{ route('admin.product-icons.edit') }}">Добавить иконку</a></small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Главная</a></li>
		<li class="active">Иконки преимуществ</li>
	</ol>
@stop

@section('content')
	<div class="box box-solid">
		<div class="box-body">
			@if (count($items))
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Иконка</th>
							<th>Название</th>
							<th>Порядок</th>
							<th width="50"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($items as $item)
							<tr>
								<td><a href="{{ route('admin.product-icons.edit', [$item->id]) }}">
										<img src="{{ \Fanky\Admin\Models\ProductIcon::UPLOAD_URL . $item->image }}" alt="">
                                        </a></td>
								<td><a href="{{ route('admin.product-icons.edit', [$item->id]) }}">{!! $item->name !!}</a></td>
								<td><a href="{{ route('admin.product-icons.edit', [$item->id]) }}">{{ $item->order }}</a></td>
								<td>
									<a class="glyphicon glyphicon-trash" href="{{ route('admin.product-icons.delete', [$item->id]) }}"
									   style="font-size:20px; color:red;" title="Удалить" onclick="return newsDel(this)"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<p>Нет иконок</p>
			@endif
		</div>
	</div>
@stop
