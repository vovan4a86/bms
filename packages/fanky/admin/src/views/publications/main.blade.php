@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_news.js"></script>
@stop

@section('page_name')
	<h1>Статьи
		<small><a href="{{ route('admin.publications.edit') }}">Добавить статью</a></small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Главная</a></li>
		<li class="active">Статьи</li>
	</ol>
@stop

@section('content')
	<div class="box box-solid">
		<div class="box-body">
			@if (count($publications))
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="150">Дата</th>
							<th>Название</th>
							<th width="50"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($publications as $item)
							<tr>
								<td>{{ $item->dateFormat() }}</td>
								<td><a href="{{ route('admin.publications.edit', [$item->id]) }}">{{ $item->name }}</a></td>
								<td>
									<a class="glyphicon glyphicon-trash" href="{{ route('admin.publications.delete', [$item->id]) }}" style="font-size:20px; color:red;" title="Удалить" onclick="return newsDel(this)"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
                {!! $publications->render() !!}
			@else
				<p>Нет статей!</p>
			@endif
		</div>
	</div>
@stop