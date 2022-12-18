@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_partners.js"></script>
@stop

@section('page_name')
	<h1>Партнеры и поставщики
		<small><a href="{{ route('admin.partners.edit') }}">Добавить партнера</a></small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Главная</a></li>
		<li class="active">Партнеры и поставщики</li>
	</ol>
@stop

@section('content')
	<div class="box box-solid">
		<div class="box-body">
			@if (count($partners))
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="100">Изображение</th>
							<th width="120">Ширина</th>
							<th width="120">Высота</th>
							<th>Название</th>
							<th>Сайт</th>
							<th width="120">Сортировка</th>
							<th width="50"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($partners as $item)
							<tr>
								<td style="text-align: center;">
									@if($item->image)
										<img src="{{ $item->thumb(1) }}" alt="{{ $item->name }}"></td>
								@else
									<img src="{{ \Fanky\Admin\Models\News::NO_IMAGE }}" alt="Не загружено" title="Не загружено" width="50" height="50"></td>
								@endif

								<td>
									<form class="input-group input-group-sm"
										  action="{{ route('admin.partners.update-width', [$item->id]) }}"
										  onsubmit="updateImageWidth(this, event)">
										<input type="number" name="image_width" class="form-control" step="1" value="{{ $item->image_width }}">
										<span class="input-group-btn">
											<button class="btn btn-success btn-flat" type="submit">
											   <span class="glyphicon glyphicon-ok"></span>
											</button>
										</span>
									</form>
								</td>
								<td>
									<form class="input-group input-group-sm"
										  action="{{ route('admin.partners.update-height', [$item->id]) }}"
										  onsubmit="updateImageHeight(this, event)">
										<input type="number" name="image_height" class="form-control" step="1" value="{{ $item->image_height }}">
										<span class="input-group-btn">
											<button class="btn btn-success btn-flat" type="submit">
											   <span class="glyphicon glyphicon-ok"></span>
											</button>
										</span>
									</form>
								</td>
								<td><a href="{{ route('admin.partners.edit', [$item->id]) }}">{{ $item->name }}</a></td>
								<td><a href="{{ route('admin.partners.edit', [$item->id]) }}">{{ $item->site }}</a></td>
								<td>
									<form class="input-group input-group-sm"
										  action="{{ route('admin.partners.update-order', [$item->id]) }}"
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
									<a class="glyphicon glyphicon-trash" href="{{ route('admin.partners.delete', [$item->id]) }}"
									   style="font-size:20px; color:red;" title="Удалить" onclick="return partnerDel(this)"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
                {!! $partners->render() !!}
			@else
				<p>Нет партнеров!</p>
			@endif
		</div>
	</div>
@stop
