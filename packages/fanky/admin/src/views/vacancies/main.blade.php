@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_vacancies.js"></script>
@stop

@section('page_name')
	<h1>Вакансии
		<small><a href="{{ route('admin.vacancies.edit') }}">Добавить вакансию</a></small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
		<li class="active">Вакансии</li>
	</ol>
@stop

@section('content')
	<div class="box box-solid">
		<div class="box-body">
			@if (count($vacancies))
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="100">Дата</th>
							<th>Город</th>
							<th>Название</th>
							<th>Зарплата</th>
							<th width="50"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($vacancies as $item)
							<tr>
								<td>{{ $item->dateFormat() }}</td>
								<td><a href="{{ route('admin.vacancies.edit', [$item->id]) }}">{{ $item->city->name }}</a></td>
								<td><a href="{{ route('admin.vacancies.edit', [$item->id]) }}">{{ $item->name }}</a></td>
								<td>{{ $item->price }}</td>
								<td>
									<a class="glyphicon glyphicon-trash" href="{{ route('admin.vacancies.delete', [$item->id]) }}"
									   style="font-size:20px; color:red;" title="Удалить" onclick="return vacancyDel(this)"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
                {!! $vacancies->render() !!}
			@else
				<p>Нет вакансий!</p>
			@endif
		</div>
	</div>
@stop
