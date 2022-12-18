<div class="tab-pane" id="files">
    @if(!$page->id)
        <div>Добавление файлов доступно только после сохранения страницы</div>
    @else
        <div style="border: 1px solid grey; padding: 10px; border-radius: 3px;">
            <div class="form-group d-flex">
                <div class="">
                    <label for="gost-file">Название файла</label>
                    <input type="text" class="param-name form-control" name="file_name" placeholder="Название">
                </div>
                <div class="">
                    <label for="gost-file">Описание файла</label>
                    <input type="text" class="param-alias form-control" name="file_description" placeholder="Описание">
                </div>
                <div class="form-group">
                    <label for="gost-file">Загрузить файл</label>
                    <input id="gost-file" type="file" name="file" accept=".pdf" value=""
                           onchange="return fileAttache(this, event)">
                    <div id="file-block"></div>
                </div>
                <div class="">
                    <a href="{{ route('admin.pages.addGostFile', $page->id) }}"
                       onclick="addGostFile(this, event)" class="btn btn-primary">
                        Добавить файл</a>
                </div>
            </div>
        </div>

        <table class="table table-hover table-condensed" id="file_list">
{{--        <table class="table table-striped" id="file_list">--}}
            <thead>
            <tr>
                <th width="250">Название</th>
                <th>Описание</th>
                <th width="100">Файл</th>
                <th width="120">Сортировка</th>
                <th width="100"></th>
            </tr>
            </thead>
            <tbody>
                @foreach ($page->gostFiles as $file)
                    @include('admin::pages.tabs.file_row', ['file' => $file])
                @endforeach
            </tbody>
        </table>
    @endif
</div>
