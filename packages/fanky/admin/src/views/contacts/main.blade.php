@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_contacts.js"></script>
@stop

@section('page_name')
	<h1>Контакты
		<small><a href="{{ route('admin.contacts.edit') }}">Добавить контакт</a></small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
		<li class="active">Контакты</li>
	</ol>
@stop

@section('content')
	<div class="box box-solid">
		<div class="box-body">
			@if (count($contacts))
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Город</th>
							<th>Заголовок</th>
							<th>Телефон</th>
							<th>Email</th>
							<th width="120">Сортировка</th>
							<th width="50"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($contacts as $item)
							<tr>
								<td><a href="{{ route('admin.contacts.edit', [$item->id]) }}">{{ $item->city->name }}</a></td>
								<td><a href="{{ route('admin.contacts.edit', [$item->id]) }}">{!! $item->title !!}</a></td>
								<td><a href="{{ route('admin.contacts.edit', [$item->id]) }}">{{ $item->phone1 }}</a></td>
								<td><a href="{{ route('admin.contacts.edit', [$item->id]) }}">{{ $item->email }}</a></td>
								<td>
									<form class="input-group input-group-sm"
										  action="{{ route('admin.contacts.update-order', [$item->id]) }}"
										  onsubmit="updateOrder(this, event)">
										<input type="number" name="order" class="form-control" step="1" value="{{ $item->order }}">
										<span class="input-group-btn">
											<button class="btn btn-success btn-flat" type="submit">
											   <span class="glyphicon glyphicon-ok"></span>
											</button>
										</span>
									</form>
								</td>
								<td>
									<a class="glyphicon glyphicon-trash" href="{{ route('admin.contacts.delete', [$item->id]) }}"
									   style="font-size:20px; color:red;" title="Удалить" onclick="return contactDel(this)"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
                {!! $contacts->render() !!}
			@else
				<p>Нет контактов!</p>
			@endif
		</div>
	</div>
@stop
