@extends('admin::template')

@section('scripts')
	<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="/adminlte/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/adminlte/plugins/jstree/dist/jstree.min.js"></script>
    <link href="/adminlte/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/adminlte/interface_action.js"></script>
@stop

@section('page_name')
	<h1>Акции</h1>
@stop

@section('breadcrumb')
	<ol class="breadcrumb">
		<li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i>Главная</a></li>
		<li class="active">Акции</li>
	</ol>
@stop

@section('content')
	<div class="row">
		<div class="col-md-3">
			<div class="box box-solid">
				<div id="catalog-tree" class="box-body">
					<a href="{{ route('admin.actions.actionEdit') }}" onclick="return catalogContent(this)">Добавить акцию</a>
					<br><br>
                    <div id="pages-tree"></div>
				</div>
			</div>
		</div>

		<div id="catalog-content" class="col-md-9">
			{!! $content ?? '' !!}
		</div>
	</div>
@stop