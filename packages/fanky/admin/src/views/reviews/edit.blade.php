@extends('admin::template')

@section('scripts')
	<script type="text/javascript" src="/adminlte/interface_reviews.js"></script>
@stop

@section('page_name')
	<h1>
		Отзывы
		<small>{{ $review->id ? 'Редактировать' : 'Новый' }}</small>
	</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Главная</a></li>
		<li><a href="{{ route('admin.reviews') }}">Отзывы</a></li>
		<li class="active">{{ $review->id ? 'Редактировать' : 'Новый' }}</li>
	</ol>
@stop

@section('content')
	<form action="{{ route('admin.reviews.save') }}" onsubmit="return reviewsSave(this, event)">
		<input type="hidden" name="id" value="{{ $review->id }}">

		<div class="box box-solid">
			<div class="box-body">

				<div class="form-group" style="width:200px;">
					<label for="review-type">Категория</label>
					<select id="review-type" class="form-control" name="type">
						<option value=""></option>
						@foreach ($review::$types as $typeId => $typeName)
							<option value="{{ $typeId }}" {{ $review->type == $typeId ? 'selected' : '' }}>{{ $typeName }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="review-adress">Адрес</label>
					<input id="review-adress" class="form-control" type="text" name="adress" value="{{ $review->adress }}">
				</div>

				<div class="form-group">
					<label for="review-text">Название</label>
					<textarea id="review-text" class="form-control" name="text" rows="6">{{ $review->text }}</textarea>
				</div>

				<div class="form-group">
					<label for="review-video">Видео</label>
					<input id="review-video" class="form-control" type="text" name="video" value="" placeholder="Ссылка YouTube...">
					<div id="review-video-block">
						@if ($review->video)
							<img class="img-polaroid" src="{{ $review->video_thumb }}" height="100" onclick="popupVideo('{{ $review->video_src }}')">
						@else
							<p class="text-yellow">Видео не добавлено.</p>
						@endif
					</div>
				</div>

				<div class="form-group">
					<label>
						<input type="checkbox" class="minimal" name="on_main" value="1" {{ $review->on_main == 1 ? 'checked' : '' }}>
						Показывать на главной
					</label>
				</div>

			</div>

			<div class="box-footer">
    			<button type="submit" class="btn btn-primary">Сохранить</button>
    		</div>
		</div>
	</form>
@stop