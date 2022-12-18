<tr id="file{{ $file->id }}">
    <td>{{ $file->file_name }}</td>
    <td>{{ $file->file_description }}</td>
    <td>
        @if($file->file)
            <img class="img-polaroid" src="/static/images/common/ico_pdf.svg"
                 height="30" data-image="static/images/common/ico_pdf.svg">
        @else
            <p>нет файла</p>
        @endif
    </td>
    <td>
        <div class="input-group input-group-sm">
            <input type="number" name="file_order" class="form-control" step="1" value="{{ $file->order }}">
            <span class="input-group-btn">
                <a href="{{ route('admin.pages.updateGostFileOrder', [$file->id]) }}"
                   class="btn btn-success btn-flat" onclick="updateGostFileOrder(this, event)">
                   <span class="glyphicon glyphicon-ok"></span>
                </a>
            </span>
        </div>
    </td>
    <td>
        <a href="{{ route('admin.pages.delGostFile', [$file->id]) }}"
           class="btn btn-default del-param" onclick="delGostFile(this, event)">
            <i class="fa fa-trash text-red"></i>
        </a>
    </td>
</tr>
