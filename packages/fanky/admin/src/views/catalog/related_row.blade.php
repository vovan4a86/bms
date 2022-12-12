<tr id="related{{ $related->id }}">
    <td>{{ $related->related_name }}</td>
    <td>
        <a href="{{ route('admin.catalog.del_related', [$related->id]) }}"
           class="btn btn-default del-param" onclick="delRelated(this, event)">
            <i class="fa fa-trash text-red"></i></a>
    </td>
</tr>
