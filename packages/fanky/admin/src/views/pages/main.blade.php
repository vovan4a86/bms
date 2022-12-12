@extends('admin::template')

@section('head')
	<link href="/adminlte/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
	<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/adminlte/plugins/jstree/dist/jstree.min.js"></script>
    <script type="text/javascript" src="/adminlte/interface_pages.js"></script>
	<script type="text/javascript" src="/adminlte/interface_settings.js"></script>
	<script type="text/javascript" src="/adminlte/interface_gallery.js"></script>
@stop

@section('page_name')
	<h1>Структура сайта</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> Главная</a></li>
		<li class="active">Структура сайта</li>
	</ol>
@stop

@section('content')
	<div class="row">
		<div class="col-md-3">
			<div class="box box-solid">
				<div class="box-body">
					
					<a href="{{ route('admin.pages.edit') }}" onclick="return pageContent(this)" style="display:inline-block;margin-bottom:10px;">Добавить страницу</a>
					<div id="pages-tree"></div>
				</div>
			</div>
		</div>

		<div id="page-content" class="col-md-9">
			{!! $content ?? '' !!}
		</div>
	</div>
@stop