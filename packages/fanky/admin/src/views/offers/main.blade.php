@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_news.js"></script>
@stop

@section('page_name')
	<h1>Спецпредложения
		<small><a href="{{ route('admin.offers.edit') }}">Добавить спецпредложение</a></small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
		<li class="active">Спецпредложения</li>
	</ol>
@stop

@section('content')
	<div class="box box-solid">
		<div class="box-body">
			@if (count($offers))
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="150">Дата</th>
							<th>Название</th>
							<th width="50"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($offers as $item)
							<tr>
								<td>{{ $item->dateFormat() }}</td>
								<td><a href="{{ route('admin.offers.edit', [$item->id]) }}">{{ $item->name }}</a></td>
								<td>
									<a class="glyphicon glyphicon-trash" href="{{ route('admin.offers.delete', [$item->id]) }}"
									   style="font-size:20px; color:red;" title="Удалить" onclick="return newsDel(this)"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
                {!! $offers->render() !!}
			@else
				<p>Нет спецпредложений!</p>
			@endif
		</div>
	</div>
@stop